<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'device_type',
        'brand',
        'model',
        'serial_number',
        'mac_address',
        'ip_address',
        'location',
        'status',
        'client_id',
        'installation_date',
        'warranty_expiry',
        'purchase_price',
        'specifications',
        'notes',
    ];

    protected $casts = [
        'installation_date' => 'date',
        'warranty_expiry' => 'date',
        'purchase_price' => 'decimal:2',
        'specifications' => 'array',
    ];

    /**
     * Get the client that owns the device
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Check if device is under warranty
     */
    public function isUnderWarranty()
    {
        return $this->warranty_expiry && $this->warranty_expiry->isFuture();
    }

    /**
     * Scope for active devices
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for devices by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('device_type', $type);
    }
}
