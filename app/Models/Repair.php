<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    //


    public $incrementing = false; // Disable auto-incrementing

    protected $keyType = 'string'; // Set primary key type to string

    protected static function boot()
    {
        parent::boot();

        // Automatically generate a UUID when creating a new model
        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }
}
