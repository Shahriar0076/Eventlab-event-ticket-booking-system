<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;


class EventController extends Controller
{
    public function buyTicket($eventId)
    {
        $pageTitle = 'Reserve a Spot';
        $user      = auth()->user();
        $event     = Event::where('id', $eventId)->futureEvents()->approved()->firstOrFail();
        $quantity  = request()->qty;

        if (!$quantity) return returnBack('Ticket quantity not selected');
        if ($quantity > $event->seatsAvailable())  return returnBack('Ticket quantity exceeds the limit');
        $total = $quantity * $event->price;

        return view('Template::user.event.ticket.buy', compact('pageTitle', 'user', 'event', 'quantity', 'total'));
    }

    public function confirmPurchase(Request $request, $eventId)
    {
        $request->validate([
            'quantity'             => 'required|integer|gt:0',
            'details'              => 'required|array|min:' . $request->quantity,
            'details.*.first_name' => 'required|string|max:255',
            'details.*.last_name'  => 'required|string|max:255',
            'details.*.email'      => 'required|email',
        ], [
            'quantity.required'             => 'Invalid Request',
            'quantity.integer'              => 'Invalid Request',
            'quantity.gt'                   => 'Invalid Request',
            'details.required'              => 'Invalid Request',
            'details.array'                 => 'Invalid Request',
            'details.min'                   => 'Invalid Request',
            'details.*.first_name.required' => 'The first name is required for all tickets',
            'details.*.first_name.string'   => 'The first name must be a string',
            'details.*.first_name.max'      => 'The first name may not be greater than :max characters',
            'details.*.last_name.required'  => 'The last name is required for all tickets',
            'details.*.last_name.string'    => 'The last name must be a string',
            'details.*.last_name.max'       => 'The last name may not be greater than :max characters',
            'details.*.email.required'      => 'The email is required for all tickets',
            'details.*.email.email'         => 'The email must be a valid email address',
        ]);

        $event   = Event::where('id', $eventId)->futureEvents()->approved()->firstOrFail();

        if ($request->quantity > $event->seatsAvailable()) return returnBack('The number of seats is not available');

        $order              = new Order();
        $order->event_id    = $event->id;
        $order->user_id     = auth()->user()->id;
        $order->price       = $event->price;
        $order->quantity    = $request->quantity;
        $order->total_price = $event->price * $request->quantity;
        $order->details     = array_values($request->details);

        if ($event->price == 0) {
            $order->payment_status = Status::PAID;
            $order->status         = Status::ORDER_COMPLETED;
            $order->save();

            //send confirmation email to user
            notify($order->user, 'ORDER_CONFIRMED', [
                'trx'          => "N/A",
                'order_number' => $order->id,
                'event_name'   => $order->event->title,
                'start_date'   => $order->event->start_date,
                'end_date'     => $order->event->end_date,
                'price'        => showAmount($order->price),
                'quantity'     => $order->quantity,
                'total_price'  => showAmount($order->total_price),
                'ticket_url'   => ticketDownloadUrl($order->id),
            ]);
        } else {

            $order->payment_status = Status::UNPAID;
            $order->status         = Status::ORDER_PAYMENT_PENDING;
            $order->save();

            //send confirmation email to user
            notify($order->user, 'ORDER_INITIATE', [
                'order_number' => $order->id,
                'event_name'   => $order->event->title,
                'start_date'   => $order->event->start_date,
                'end_date'     => $order->event->end_date,
                'price'        => showAmount($order->price),
                'quantity'     => $order->quantity,
                'total_price'  => showAmount($order->total_price),
                'ticket_url'   => ticketDownloadUrl($order->id),
            ]);
        }

        $event->seats_booked += $order->quantity;
        $event->save();

        if ($event->price == 0) return returnBack('Ticket purchased successfully', 'success', route: 'user.event.ticket.details', routeId: $order->id);

        return returnBack('Please make your payment', 'success', route: 'user.deposit.index', routeId: $order->id);
    }

    public function tickets()
    {
        $pageTitle = 'All Tickets';
        $tickets   = $this->filterTickets();
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function activeTickets()
    {
        $pageTitle = 'Active Tickets';
        $tickets   = $this->filterTickets('completed');
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function paymentPendingTickets()
    {
        $pageTitle = 'Payment Pending Tickets';
        $tickets   = $this->filterTickets('paymentPending');
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    public function refundedTickets()
    {
        $pageTitle = 'Refunded Tickets Tickets';
        $tickets   = $this->filterTickets('refunded');
        return view('Template::user.event.ticket.index', compact('pageTitle', 'tickets'));
    }

    protected function filterTickets($scope = null)
    {
        $tickets   = Order::userOrders();

        if ($scope) {
            $tickets = $tickets->$scope();
        }

        return $tickets->with('event')->searchable(['event:title'])->latest()->paginate(getPaginate());
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

        if (!$order->canCancel($cancelBeforeHour)) return returnBack('You can not cancel this ticket now');
        if ($order->quantity > $event->seats) return returnBack('Something went wrong');

        $trx = 'N/A';

        //if payment done refund
        if ($order->payment_status == Status::PAID && $order->price > 0) {
            $trx = getTrx();
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
            $transaction->trx          = $trx;
            $transaction->remark       = 'order_refund';
            $transaction->save();

            $organizer           = $order->event->organizer;
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
            $transaction->trx          = $trx;
            $transaction->remark       = 'order_payment';
            $transaction->save();
        }

        $event->seats_booked -= $order->quantity;
        $event->save();

        $order->status = Status::ORDER_CANCELLED;
        $order->save();

        //send confirmation email to user
        notify($order->user, 'ORDER_CANCELLED', [
            'trx'          => $trx,
            'order_number' => $order->id,
            'event_name'   => $order->event->title,
            'start_date'   => $order->event->start_date,
            'end_date'     => $order->event->end_date,
            'price'        => showAmount($order->price),
            'quantity'     => $order->quantity,
            'total_price'  => showAmount($order->total_price),
            'ticket_url'   => ticketDownloadUrl($order->id),
        ]);

        //send CANCELLED email to organizer
        notify($order->event->organizer, 'ORDER_CANCELLED', [
            'trx'          => $trx,
            'order_number' => $order->id,
            'event_name'   => $order->event->title . " cancelled by user " . $order->user->username,
            'start_date'   => $order->event->start_date,
            'end_date'     => $order->event->end_date,
            'price'        => showAmount($order->price),
            'quantity'     => $order->quantity,
            'total_price'  => showAmount($order->total_price),
            'ticket_url'   => ticketDownloadUrl($order->id),
        ]);

        return returnBack('Ticket cancelled successfully', 'success');
    }
}
