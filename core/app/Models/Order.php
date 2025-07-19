<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use GlobalStatus;

    protected $casts = [
        'details' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class, 'order_id')->latest('id')->where('status', Status::YES);
    }

    public function statusBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->status == Status::PAYMENT_PENDING) {
                $html = '<span class="badge badge--warning">' . trans('Payment Pending') . '</span>';
            } elseif ($this->status == Status::ORDER_COMPLETED) {
                $html = '<span><span class="badge badge--success">' . trans('Active') . '</span></span>';
            } elseif ($this->status == Status::ORDER_CANCELLED) {
                $html = '<span><span class="badge badge--danger">' . trans('Cancelled') . '</span></span>';
            }
            return $html;
        });
    }

    public function paymentData(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->payment_status == STATUS::PAID && $this->status == Status::ORDER_CANCELLED) {
                $html = '<span class="badge badge--success">' . trans('Refunded') . '</span>';
            } elseif ($this->payment_status == Status::PAID) {
                $html = '<span class="badge badge--success">' . trans('Paid') . '</span>';
            } else {
                $html = '<span class="badge badge--warning">' . trans('Pending') . '</span>';
            }
            return $html;
        });
    }

    public function scopeUserOrders($query)
    {
        return $query->where('user_id', auth()->user()->id)->orderBy('id', 'DESC');
    }

    public function scopeOrganizerTicketsSold($query, $organizerId = 0)
    {
        if (!$organizerId) {
            $organizerId = authOrganizerId();
        }

        $query->whereHas('event', function ($query) use ($organizerId) {
            $query->where('organizer_id', $organizerId);
        });
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', Status::ORDER_COMPLETED)->orderBy('id', 'DESC');
    }
    public function scopePaymentPending($query)
    {
        return $query->where('status', Status::ORDER_PAYMENT_PENDING)->orderBy('id', 'DESC');
    }
    public function scopeCancelled($query)
    {
        return $query->where('status', Status::ORDER_CANCELLED)->orderBy('id', 'DESC');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', Status::PAID)->orderBy('id', 'DESC');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', Status::UNPAID)->orderBy('id', 'DESC');
    }

    public function scopeRefunded($query)
    {
        return $query->where('payment_status', Status::PAID)->where('status', Status::ORDER_CANCELLED)->orderBy('id', 'DESC');
    }

    public function canCancel($hours)
    {
        $eventStartDateTime = $this->event->start_date . ' ' . $this->event->start_time;
        $eventStartTime = Carbon::parse($eventStartDateTime);
        $currentTime = Carbon::now();

        // Check if the event starts within 4 hours from now
        return $currentTime->diffInHours($eventStartTime, false) > $hours;
    }
}
