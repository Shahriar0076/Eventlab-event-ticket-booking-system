<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function scopeUserLogin($query)
    {
        return $query->where('user_id', '!=', 0);
    }

    public function scopeOrganizerLogin($query)
    {
        return $query->where('organizer_id', '!=', 0);
    }
}
