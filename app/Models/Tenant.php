<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;


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

    protected $fillable = ['first_name', 'last_name', 'email', 'phone'];

    public function fullName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
