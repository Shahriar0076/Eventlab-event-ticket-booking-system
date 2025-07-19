<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{

    public function events()
    {
        return $this->belongsTo(Event::class);
    }

}
