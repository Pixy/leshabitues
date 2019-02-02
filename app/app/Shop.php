<?php

namespace App;

use Illuminate\Database\Eloquent\Model;;

class Shop extends Model
{
    /*
     * Allow every fields to be mass assignable.
     */
    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        // Generate the hash of object
        static::created(function ($shop) {
            $shop->update(['hash' => computeHash($shop->attributes)]);
        });
    }
}
