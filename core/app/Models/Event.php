<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use GlobalStatus;

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    public function galleryImages()
    {
        return $this->hasMany(GalleryImage::class);
    }

    public function speakers()
    {
        return $this->hasMany(Speaker::class);
    }

    public function tickets()
    {
        return $this->hasMany(Order::class);
    }

    public function scopeFutureEvents($query)
    {
        return $query->where('start_date', '>', Carbon::now());
    }

    public function scopeRunning($query)
    {
        return $query->where('start_date', '<=', Carbon::now()) // Event has started
            ->where('end_date', '>=', Carbon::now()); // Event has not ended yet
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', Carbon::now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', Status::EVENT_DRAFT);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', Status::EVENT_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', Status::EVENT_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', Status::EVENT_REJECTED);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::YES);
    }

    public function scopeOrganizerEvents($query)
    {
        return $query->where('organizer_id', authOrganizerId());
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'user_event', 'event_id', 'user_id')->withTimestamps();
    }

    public function seatsAvailable()
    {
        return $this->seats - $this->seats_booked;
    }

    public function featuredBadge(): Attribute
    {
        return new Attribute(function () {
            $html = '';
            if ($this->is_featured) {
                $html = '<span class="badge badge--success">' . trans('Yes') . '</span>';
            } else {
                $html = '<span class="badge badge--warning">' . trans('No') . '</span>';
            }
            return $html;
        });
    }

    public function statusBadge($styleTwo = false)
    {
        $html = '';
        $style = '';
        if ($styleTwo) {
            $style = 'badge-two';
        }
        if ($this->status == Status::EVENT_APPROVED) {
            $html = '<span class="badge badge--success ' . $style . '">' . trans('Approved') . '</span>';
        }
        if ($this->status == Status::EVENT_PENDING) {
            $html = '<span class="badge badge--warning ' . $style . '">' . trans('Pending') . '</span>';
        }
        if ($this->status == Status::EVENT_REJECTED) {
            $html = '<span class="badge badge--danger ' . $style . '">' . trans('Rejected') . '</span>';
        }
        if ($this->status == Status::EVENT_DRAFT) {
            $html = '<span class="badge badge--info ' . $style . '">' . trans('Draft') . '</span>';
        }

        return $html;
    }

    public function isExpired()
    {
        return $this->end_date < Carbon::now();
    }
}
