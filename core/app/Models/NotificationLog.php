<?php

namespace App\Models;

use App\Traits\ApiQuery;
use Illuminate\Database\Eloquent\Model;

class NotificationLog extends Model
{
    use ApiQuery;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function scopeUserNotification($query)
    {
        return $query->where('user_id', '!=', 0);
    }

    public function scopeOrganizerNotification($query)
    {
        return $query->where('organizer_id', '!=', 0);
    }
}
