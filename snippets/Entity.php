<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Entity extends Model
{
    use SoftDeletes;
    use Sluggable;

    protected $dates = ['deleted_at'];

    protected $fillable = ['title','body','order','active'];

    public function image()
    {
      return $this->belongsTo('App\Models\Image');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function routeImagePath()
    {
        return Storage::disk('public')->url('/entitys');
    }
}
