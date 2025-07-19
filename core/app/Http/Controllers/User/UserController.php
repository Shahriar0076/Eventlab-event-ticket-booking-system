<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\GoogleAuthenticator;
use App\Models\Deposit;
use App\Models\DeviceToken;
use App\Models\Event;
use App\Models\Form;
use App\Models\Order;
use App\Models\Organizer;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function home()
    {
        $pageTitle = 'Dashboard';
        $user      = auth()->user();
        $orders    = Order::userOrders();

        $tickets                        = (clone $orders)->with('event')->limit(5)->get();
        $widget['total_tickets']        = (clone $orders)->count();
        $widget['refunded_tickets']     = (clone $orders)->refunded()->count();
        $widget['liked_events']         = $user->events->count();
        $widget['following_organizers'] = $user->organizers->count();
        $widget['total_deposit']        = Deposit::where('user_id', auth()->user()->id)->deposits()->where('status', '!=', Status::PAYMENT_INITIATE)->successful()->sum('amount');

        return view('Template::user.dashboard', compact('pageTitle', 'tickets', 'widget'));
    }

    public function depositHistory(Request $request)
    {
        $pageTitle = 'Deposit History';
        $deposits = Deposit::where('user_id', auth()->user()->id)->deposits()->where('status', '!=', Status::PAYMENT_INITIATE)->searchable(['trx'])->with(['gateway'])->orderBy('id', 'desc')->paginate(getPaginate());
        return view('Template::user.deposit_history', compact('pageTitle', 'deposits'));
    }

    public function show2faForm()
    {
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . gs('site_name'), $secret);
        $pageTitle = '2FA Security';
        return view('Template::user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user, $request->code, $request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = Status::ENABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator activated successfully'];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', 'Wrong verification code'];
            return back()->withNotify($notify);
        }
    }

    public function disable2fa(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user, $request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = Status::DISABLE;
            $user->save();
            $notify[] = ['success', 'Two factor authenticator deactivated successfully'];
        } else {
            $notify[] = ['error', 'Wrong verification code'];
        }
        return back()->withNotify($notify);
    }

    public function transactions()
    {
        $pageTitle = 'Transactions';
        $remarks = Transaction::distinct('remark')->orderBy('remark')->get('remark');

        $transactions = Transaction::where('user_id', auth()->id())->searchable(['trx'])->filter(['trx_type', 'remark'])->orderBy('id', 'desc')->paginate(getPaginate());

        return view('Template::user.transactions', compact('pageTitle', 'transactions', 'remarks'));
    }

    public function kycForm()
    {
        if (auth()->user()->kv == Status::KYC_PENDING) {
            $notify[] = ['error', 'Your KYC is under review'];
            return to_route('user.home')->withNotify($notify);
        }

        if (auth()->user()->kv == Status::KYC_VERIFIED) {
            $notify[] = ['error', 'You are already KYC verified'];
            return to_route('user.home')->withNotify($notify);
        }

        $pageTitle = 'KYC Form';
        $form = Form::where('act', 'user_kyc')->first();
        return view('Template::user.kyc.form', compact('pageTitle', 'form'));
    }

    public function kycData()
    {
        $user = auth()->user();
        $pageTitle = 'KYC Data';
        return view('Template::user.kyc.info', compact('pageTitle', 'user'));
    }

    public function kycSubmit(Request $request)
    {
        $form = Form::where('act', 'user_kyc')->firstOrFail();
        $formData = $form->form_data;
        $formProcessor = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $user = auth()->user();
        foreach (@$user->kyc_data ?? [] as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $userData = $formProcessor->processFormData($request, $formData);
        $user->kyc_data = $userData;
        $user->kyc_rejection_reason = null;
        $user->kv = Status::KYC_PENDING;
        $user->save();

        $notify[] = ['success', 'KYC data submitted successfully'];
        return to_route('user.home')->withNotify($notify);
    }

    public function userData()
    {
        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $pageTitle  = 'User Data';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        return view('Template::user.user_data', compact('pageTitle', 'user', 'countries', 'mobileCode'));
    }

    public function userDataSubmit(Request $request)
    {

        $user = auth()->user();

        if ($user->profile_complete == Status::YES) {
            return to_route('user.home');
        }

        $countryData  = (array)json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryCodes = implode(',', array_keys($countryData));
        $mobileCodes  = implode(',', array_column($countryData, 'dial_code'));
        $countries    = implode(',', array_column($countryData, 'country'));

        $request->validate([
            'country_code' => 'required|in:' . $countryCodes,
            'country'      => 'required|in:' . $countries,
            'mobile_code'  => 'required|in:' . $mobileCodes,
            'username'     => 'required|unique:users|min:6',
            'mobile'       => ['required', 'regex:/^([0-9]*)$/', Rule::unique('users')->where('dial_code', $request->mobile_code)],
        ]);

        $user->country_code = $request->country_code;
        $user->mobile       = $request->mobile;
        $user->username     = $request->username;


        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $user->country_name = @$request->country;
        $user->dial_code = $request->mobile_code;

        $user->profile_complete = Status::YES;
        $user->save();

        return to_route('user.home');
    }


    public function addDeviceToken(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return ['success' => false, 'errors' => $validator->errors()->all()];
        }

        $deviceToken = DeviceToken::where('token', $request->token)->first();

        if ($deviceToken) {
            return ['success' => true, 'message' => 'Already exists'];
        }

        $deviceToken          = new DeviceToken();
        $deviceToken->user_id = auth()->user()->id;
        $deviceToken->token   = $request->token;
        $deviceToken->is_app  = Status::NO;
        $deviceToken->save();

        return ['success' => true, 'message' => 'Token saved successfully'];
    }

    public function downloadAttachment($fileHash)
    {
        $filePath = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

    public function followOrganizer($organizerId)
    {
        $user      = auth()->user();
        $organizer = Organizer::active()->profileCompleted()->findOrFail($organizerId);
        $user->organizers()->attach($organizer);

        $notify[]  = ['success', 'Followed organizer successfully'];
        return back()->withNotify($notify);
    }

    public function unfollowOrganizer($organizerId)
    {
        $user      = auth()->user();
        $organizer = Organizer::active()->profileCompleted()->findOrFail($organizerId);

        $user->organizers()->detach($organizer);

        $notify[] = ['success', 'Unfollowed organizer successfully'];
        return back()->withNotify($notify);
    }

    public function followingOrganizers()
    {
        $pageTitle  = 'Following Organizers';
        $user       = auth()->user();
        $organizers = Organizer::active()
            ->profileCompleted()
            ->with('followers')
            ->orderByDesc('is_featured')
            ->whereHas('followers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->paginate(getPaginate());

        return view('Template::user.following_organizers', compact('pageTitle', 'organizers'));
    }

    public function likeEvent($eventId)
    {
        $user  = auth()->user();
        $event = Event::approved()->find($eventId);

        if (!$event) return jsonResponse('Event not found');

        if ($user->isLiked($event)) {
            $user->events()->detach($event);

            $liked   = false;
            $message = 'Un-liked event successfully';
        } else {
            $user->events()->attach($event);

            $liked   = true;
            $message = 'Liked event successfully';
        }

        return response()->json([
            'success'   => true,
            'message'   => $message,
            'liked'     => $liked,
            'likeCount' => $event->likes->count()
        ]);
    }

    public function likedEvents()
    {
        $pageTitle = 'Liked Events';
        $user      = auth()->user();
        $events    = Event::whereHas('likes', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->with('location', 'organizer', 'likes')->paginate(getPaginate(12));

        return view('Template::user.event.liked', compact('pageTitle', 'events'));
    }

    public function tickets()
    {
        $pageTitle = 'All Tickets';
        $tickets   = Order::userOrders()->with('event')->latest()->paginate(getPaginate());
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function paymentCompletedTickets()
    {
        $pageTitle = 'Payment Pending Tickets';
        $tickets   = Order::userOrders()->completed()->with('event')->latest()->paginate(getPaginate());
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function cancelledTickets()
    {
        $pageTitle = 'Cancelled Tickets';
        $tickets   = Order::userOrders()->cancelled()->with('event')->latest()->paginate(getPaginate());
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function refundedTickets()
    {
        $pageTitle = 'Refunded Tickets Tickets';
        $tickets   = Order::userOrders()->refunded()->with('event')->latest()->paginate(getPaginate());
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function ticketDetails($ticketId)
    {
        $pageTitle = 'Ticket Details';
        $ticket    = Order::userOrders()->with('event')->findOrFail($ticketId);
        return view('Template::user.event.ticket.details', compact('pageTitle', 'ticket'));
    }

    public function cancelTicket($ticketId)
    {
        $user             = auth()->user();
        $order            = Order::userOrders()->where('status', '!=', Status::ORDER_CANCELLED)->with('event')->findOrFail($ticketId);
        $event            = $order->event;
        $cancelBeforeHour = gs('cancel_time');

        if (!$order->canCancel($cancelBeforeHour)) {
            $notify[] = ['error', 'You can not cancel this ticket now'];
            return back()->withNotify($notify);
        }

        //if payment done refund
        if ($order->payment_status == Status::PAID && $order->price > 0) {
            $user->balance += $order->total_price;
            $user->save();

            $eventName = $event->title;

            $transaction               = new Transaction();
            $transaction->user_id      = $order->user_id;
            $transaction->amount       = $order->total_price;
            $transaction->post_balance = $user->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '+';
            $transaction->details      = "Refund Completed for $eventName";
            $transaction->trx          = getTrx();
            $transaction->remark       = 'order_refund';
            $transaction->save();

            //send confirmation email to user
            notify($order->user, 'ORDER_CANCELLED', [
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

            $organizer          = $order->event->organizer;
            $organizer->balance -= $order->total_price;
            $organizer->save();
            $userUsername = $order->user->username;

            $transaction               = new Transaction();
            $transaction->organizer_id = $organizer->id;
            $transaction->amount       = $order->total_price;
            $transaction->post_balance = $organizer->balance;
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = "Refund Completed for $eventName, ticket cancelled by user $userUsername";
            $transaction->trx          = getTrx();
            $transaction->remark       = 'order_payment';
            $transaction->save();

            //send CANCELLED email to organizer
            notify($order->event->organizer, 'ORDER_CANCELLED', [
                'trx'          => $transaction->trx,
                'order_number' => $order->id,
                'event_name'   => $order->event->title . " cancelled by user " . $order->user->username,
                'start_date'   => $order->event->start_date,
                'end_date'     => $order->event->end_date,
                'price'        => showAmount($order->price),
                'quantity'     => $order->quantity,
                'total_price'  => showAmount($order->total_price),
                'ticket_url'   => ticketDownloadUrl($order->id),
            ]);
        }

        $event->seats_booked -= $order->quantity;
        $event->save();

        $order->status = Status::ORDER_CANCELLED;
        $order->save();

        $notify[] = ['success', 'Ticket cancelled Successfully'];
        return back()->withNotify($notify);
    }
}
