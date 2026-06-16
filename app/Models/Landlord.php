<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Landlord extends Model
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

    protected $fillable = ['first_name', 'last_name', 'user_id', 'email', 'address', 'phone'];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
