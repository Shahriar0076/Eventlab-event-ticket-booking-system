<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function scopeUserTransaction($query)
    {
        return $query->where('user_id', '!=', 0);
    }

    public function scopeOrganizerTransaction($query)
    {
        return $query->where('organizer_id', '!=', 0);
    }
}
