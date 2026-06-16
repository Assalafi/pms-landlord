<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Disable auto-incrementing and set the primary key type to string
    public $incrementing = false;
    protected $keyType = 'string';

    // Table name (optional, if different from 'activities')
    protected $table = 'activities';

    // Mass assignable attributes
    protected $fillable = [
        'tenant_id',
        'unit_id',
        'amount',
        'start_date',
        'end_date',
        'status',
    ];

    // Automatically generate a UUID when creating a new model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    // Relationships
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class, 'activity_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Accessors
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2);
    }

    // Disable timestamps if not needed
    public $timestamps = true; // Set to false if `created_at` and `updated_at` are not used
}
