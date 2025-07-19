<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\GlobalStatus;
use App\Traits\SortOrder;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use GlobalStatus, SortOrder;

    public function scopeActive($query)
    {
        return $query->where('status', Status::ENABLE);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
