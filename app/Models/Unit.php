<?php

namespace App\Models;
use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
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

    protected $fillable = ['property_id', 'landlord_id', 'tenant_id', 'name', 'no_of_rooms', 'no_of_baths', 'amount', 'status'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
    public function previousTenants()
    {
        return $this->belongsToMany(Tenant::class, 'activities', 'unit_id', 'tenant_id')
            ->withTimestamps();
    }
}
