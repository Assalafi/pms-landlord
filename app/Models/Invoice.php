<?php

namespace App\Models;

use Illuminate\Support\Str;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
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

    protected $fillable = [
        'activity_id',
        'unit_id',
        'landlord_id',
        'tenant_id',
        'first_name',
        'last_name',
        'phone',
        'email',
        'amount',
        'ref',
        'invoice_no',
        'status',
        'due_date',
    ];

    // Define the relationship with Tenant
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // Define the relationship with Unit
    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    // Define the relationship with Activity (if needed)
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function receipts()
    {
        return $this->hasMany(Receipt::class, 'invoice_id');
    }
}
