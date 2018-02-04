<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    public function image()
    {
        return $this->belongsTo('App\Models\Image');
    }
}
