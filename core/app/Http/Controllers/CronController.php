<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Lib\CurlRequest;
use App\Models\CronJob;
use App\Models\CronJobLog;
use App\Models\Order;
use Carbon\Carbon;
use Exception;

class CronController extends Controller
{
    public function cron()
    {
        $general            = gs();
        $general->last_cron = now();
        $general->save();

        $crons = CronJob::with('schedule');

        if (request()->alias) {
            $crons->where('alias', request()->alias);
        } else {
            $crons->where('next_run', '<', now())->where('is_running', Status::YES);
        }
        $crons = $crons->get();
        foreach ($crons as $cron) {
            $cronLog              = new CronJobLog();
            $cronLog->cron_job_id = $cron->id;
            $cronLog->start_at    = now();
            if ($cron->is_default) {
                $controller = new $cron->action[0];
                try {
                    $method = $cron->action[1];
                    $controller->$method();
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            } else {
                try {
                    CurlRequest::curlContent($cron->url);
                } catch (\Exception $e) {
                    $cronLog->error = $e->getMessage();
                }
            }
            $cron->last_run = now();
            $cron->next_run = now()->addSeconds($cron->schedule->interval);
            $cron->save();

            $cronLog->end_at = $cron->last_run;

            $startTime         = Carbon::parse($cronLog->start_at);
            $endTime           = Carbon::parse($cronLog->end_at);
            $diffInSeconds     = $startTime->diffInSeconds($endTime);
            $cronLog->duration = $diffInSeconds;
            $cronLog->save();
        }
        if (request()->target == 'all') {
            $notify[] = ['success', 'Cron executed successfully'];
            return back()->withNotify($notify);
        }
        if (request()->alias) {
            $notify[] = ['success', keyToTitle(request()->alias) . ' executed successfully'];
            return back()->withNotify($notify);
        }
    }

    public function cancelPaymentTimeoutTickets()
    {
        try {
            //not cancelled and not paid
            $paymentTimeout =  gs('payment_timeout');
            $orders = Order::where('created_at', '<', Carbon::now()->subMinutes($paymentTimeout))
                ->where('status', '!=', Status::ORDER_CANCELLED)
                ->where('payment_status', Status::UNPAID)
                ->with('user', 'event', 'event.organizer')
                ->take(100)
                ->get();

            foreach ($orders as $order) {
                $event = $order->event;

                $event->seats_booked -= $order->quantity;
                $event->save();

                $order->status = Status::ORDER_CANCELLED;
                $order->save();

                //send CANCELLED email to user
                notify($order->user, 'ORDER_CANCELLED', [
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

                //send CANCELLED email to organizer
                notify($order->event->organizer, 'ORDER_CANCELLED', [
                    'trx'          => "N/A",
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
        } catch (Exception $ex) {
            throw new \Exception($ex->getMessage());
        }

        echo 'Timeout Tickets Cancelled Successfully';
    }
}
