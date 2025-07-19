<?php

namespace App\Http\Controllers\Gateway;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\GatewayCurrency;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function deposit(Request $request, $orderId = 0)
    {
        $query  = GatewayCurrency::query();
        $gatewayCurrency  = (clone $query)->whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method')->orderby('name')->get();


        $pageTitle = 'Deposit Methods';
        $order     = null;

        if ($orderId) {
            $gatewayCurrency  = (clone $query)->whereHas('method', function ($gate) {
                $gate->where('status', Status::ENABLE)->where('method_code', '<', 1000);
            })->with('method')->orderby('name')->get();

            $pageTitle    = 'Payment Methods';
            $order        = Order::userOrders()->where('id', $orderId)->where('status', Status::ORDER_PAYMENT_PENDING)->where('payment_status', Status::UNPAID)->first();
            if (!$order) {
                $notify[] = ['error', 'You can not make payment for this order'];
                return to_route('user.home')->withNotify($notify);
            }
        }
        return view('Template::user.payment.deposit', compact('gatewayCurrency', 'pageTitle', 'order'));
    }

    public function depositInsert(Request $request, $orderId = 0)
    {
        $isRequired = $orderId && $request->gateway == "wallet" ? 'nullable' : 'required';

        $request->validate([
            'amount'   => 'required|numeric|gt:0',
            'gateway'  => 'required',
            'currency' => $isRequired,
        ]);

        if ($orderId) {
            $order        = Order::userOrders()->where('id', $orderId)->where('status', Status::ORDER_PAYMENT_PENDING)->where('payment_status', Status::UNPAID)->firstOrFail();

            $eventCreatedTime = $order->created_at;
            $eventCreatedTime = Carbon::parse($eventCreatedTime);
            $currentTime = Carbon::now();
            $diffInMinutes = $eventCreatedTime->diffInMinutes($currentTime);
            if (gs('payment_timeout') < $diffInMinutes) {
                return returnBack('Times up! You can not make payment for this ticket', route: 'user.event.ticket.details', routeId: $order->id);
            }

            $request->amount = $order->total_price;

            if ($order->status != Status::ORDER_PAYMENT_PENDING) {
                return returnBack('Unauthorized action', route: 'user.event.ticket.details', routeId: $order->id);
            }

            if ($request->gateway == "wallet") {
                $user             = $order->user;
                if ($order->total_price > $user->balance) {
                    return returnBack('Insufficient Balance', route: 'user.deposit.index', routeId: $order->id);
                }

                self::confirmOrder($order);

                $notify[] = ['success', 'Payment done successfully'];
                return to_route('user.event.ticket.details', $order->id)->withNotify($notify);
            }
        }

        $gate = GatewayCurrency::whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->where('method_code', $request->gateway)->where('currency', $request->currency)->first();

        if (!$gate) {
            $notify[] = ['error', 'Invalid gateway'];
            return back()->withNotify($notify);
        }

        if ($gate->min_amount > $request->amount || $gate->max_amount < $request->amount) {
            $notify[] = ['error', 'Please follow deposit limit'];
            return back()->withNotify($notify);
        }

        $charge      = $gate->fixed_charge + ($request->amount * $gate->percent_charge / 100);
        $payable     = $request->amount + $charge;
        $finalAmount = $payable * $gate->rate;

        $data                  = new Deposit();
        $data->user_id         = auth()->user()->id;
        if ($orderId) {
            $data->order_id    = $orderId;
        }
        $data->method_code     = $gate->method_code;
        $data->method_currency = strtoupper($gate->currency);
        $data->amount          = $request->amount;
        $data->charge          = $charge;
        $data->rate            = $gate->rate;
        $data->final_amount    = $finalAmount;
        $data->btc_amount      = 0;
        $data->btc_wallet      = "";
        $data->trx             = getTrx();

        if ($orderId) {
            $data->success_url     = route('user.event.ticket.details', $orderId);
            $data->failed_url      = route('user.event.ticket.details', $orderId);
        } else {
            $data->success_url     = urlPath('user.deposit.history');
            $data->failed_url      = urlPath('user.deposit.history');
        }
        $data->save();
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }


    public function appDepositConfirm($hash)
    {
        try {
            $id = decrypt($hash);
        } catch (\Exception $ex) {
            abort(404);
        }
        $data = Deposit::where('id', $id)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->firstOrFail();
        $user = User::findOrFail($data->user_id);
        auth()->login($user);
        session()->put('Track', $data->trx);
        return to_route('user.deposit.confirm');
    }


    public function depositConfirm()
    {
        $track   = session()->get('Track');
        $deposit = Deposit::where('trx', $track)->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'DESC')->with('gateway')->firstOrFail();

        if ($deposit->method_code >= 1000) {
            return to_route('user.deposit.manual.confirm');
        }

        $dirName = $deposit->gateway->alias;
        $new     = __NAMESPACE__ . '\\' . $dirName . '\\ProcessController';

        $data = $new::process($deposit);
        $data = json_decode($data);


        if (isset($data->error)) {
            $notify[]            = ['error', $data->message];
            return back()->withNotify($notify);
        }
        if (isset($data->redirect)) {
            return redirect($data->redirect_url);
        }

        // for Stripe V3
        if (@$data->session) {
            $deposit->btc_wallet = $data->session->id;
            $deposit->save();
        }

        $pageTitle = $deposit->order_id ? 'Order Payment Confirm' : 'Payment Confirm';
        return view("Template::$data->view", compact('data', 'pageTitle', 'deposit'));
    }


    public static function userDataUpdate($deposit, $isManual = null)
    {

        if ($deposit->status == Status::PAYMENT_INITIATE || $deposit->status == Status::PAYMENT_PENDING) {
            $deposit->status = Status::PAYMENT_SUCCESS;
            $deposit->save();

            $methodName = $deposit->methodName();

            $user = User::find($deposit->user_id);
            $user->balance += $deposit->amount;
            $user->save();

            $transaction               = new Transaction();
            $transaction->user_id      = $deposit->user_id;
            $transaction->amount       = $deposit->amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = $deposit->charge;
            $transaction->trx_type     = '+';
            if ($deposit->order_id != 0) {
                $transaction->details      = 'Payment Via ' . $methodName;
            } else {
                $transaction->details      = 'Deposit Via ' . $methodName;
            }
            $transaction->trx          = $deposit->trx;
            $transaction->remark       = 'deposit';
            $transaction->save();

            if (!$isManual) {
                $adminNotification            = new AdminNotification();
                $adminNotification->user_id   = $user->id;
                $adminNotification->title     = 'Deposit successful via ' . $methodName;
                $adminNotification->click_url = urlPath('admin.deposit.successful');
                $adminNotification->save();
            }

            notify($user, $isManual ? 'DEPOSIT_APPROVE' : 'DEPOSIT_COMPLETE', [
                'method_name'     => $methodName,
                'method_currency' => $deposit->method_currency,
                'method_amount'   => showAmount($deposit->final_amount, currencyFormat: false),
                'amount'          => showAmount($deposit->amount, currencyFormat: false),
                'charge'          => showAmount($deposit->charge, currencyFormat: false),
                'rate'            => showAmount($deposit->rate, currencyFormat: false),
                'trx'             => $deposit->trx,
                'post_balance'    => showAmount($user->balance)
            ]);


            //subtract balance and make payment
            if ($deposit->order_id != 0) {
                $order = $deposit->order;
                self::confirmOrder($order);
            }
        }
    }

    public function manualDepositConfirm()
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();
        abort_if(!$data, 404);
        if ($data->method_code > 999) {
            $pageTitle = 'Confirm Deposit';
            $method    = $data->gatewayCurrency();
            $gateway   = $method->method;
            return view('Template::user.payment.manual', compact('data', 'pageTitle', 'method', 'gateway'));
        }
        abort(404);
    }

    public function manualDepositUpdate(Request $request)
    {
        $track = session()->get('Track');
        $data = Deposit::with('gateway')->where('status', Status::PAYMENT_INITIATE)->where('trx', $track)->first();

        abort_if(!$data, 404);
        $gatewayCurrency = $data->gatewayCurrency();
        $gateway         = $gatewayCurrency->method;
        $formData        = $gateway->form->form_data;

        $formProcessor   = new FormProcessor();
        $validationRule  = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData        = $formProcessor->processFormData($request, $formData);

        $data->detail    = $userData;
        $data->status    = Status::PAYMENT_PENDING;
        $data->save();


        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $data->user->id;
        $adminNotification->title     = 'Deposit request from ' . $data->user->username;
        $adminNotification->click_url = urlPath('admin.deposit.details', $data->id);
        $adminNotification->save();

        notify($data->user, 'DEPOSIT_REQUEST', [
            'method_name'     => $data->gatewayCurrency()->name,
            'method_currency' => $data->method_currency,
            'method_amount'   => showAmount($data->final_amount, currencyFormat: false),
            'amount'          => showAmount($data->amount, currencyFormat: false),
            'charge'          => showAmount($data->charge, currencyFormat: false),
            'rate'            => showAmount($data->rate, currencyFormat: false),
            'trx'             => $data->trx
        ]);

        $notify[] = ['success', 'You have deposit request has been taken'];
        return to_route('user.deposit.history')->withNotify($notify);
    }

    public static function confirmOrder($order)
    {
        $trx            = getTrx();
        $eventName      = $order->event->title;
        $user           = $order->user;
        $user->balance -= $order->total_price;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $order->user_id;
        $transaction->amount       = $order->total_price;
        $transaction->post_balance = $user->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '-';
        $transaction->details      = "Payment Completed for $eventName";
        $transaction->trx          = $trx;
        $transaction->remark       = 'order_payment';
        $transaction->save();

        //send confirmation email to user
        notify($order->user, 'ORDER_CONFIRMED', [
            'trx'          => $transaction->trx,
            'order_number' => $order->id,
            'event_name'   => $order->event->title,
            'start_date'   => $order->event->start_date,
            'end_date'     => $order->event->end_date,
            'price'        => showAmount($order->price),
            'quantity'     => $order->quantity,
            'total_price'  => showAmount($order->total_price),
            'ticket_url'   => ticketDownloadUrl($order->id),
        ]);


        $organizer           = $order->event->organizer;
        $organizer->balance += $order->total_price;
        $organizer->save();

        $userUsername = $order->user->username;

        $transaction               = new Transaction();
        $transaction->organizer_id = $organizer->id;
        $transaction->amount       = $order->total_price;
        $transaction->post_balance = $organizer->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->details      = "Payment Completed for $eventName, ticket purchased by $userUsername";
        $transaction->trx          = $trx;
        $transaction->remark       = 'order_payment';
        $transaction->save();

        notify($order->event->organizer, 'ORDER_RECEIVED', [
            'trx'          => $transaction->trx,
            'order_number' => $order->id,
            'event_name'   => $order->event->title,
            'start_date'   => $order->event->start_date,
            'end_date'     => $order->event->end_date,
            'price'        => showAmount($order->price),
            'quantity'     => $order->quantity,
            'total_price'  => showAmount($order->total_price),
            'ticket_url'   => ticketDownloadUrl($order->id),
        ]);

        //change order status
        $order->payment_status = Status::PAID; //order payment done
        $order->status         = Status::ORDER_COMPLETED; //order processing
        $order->save();
    }
}
