<?php

namespace App\Models;

use App\Constants\Status;
use App\Traits\SortOrder;
use App\Traits\GlobalStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
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

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', Status::YES);
    }
}
