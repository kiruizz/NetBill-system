<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'subscription_id',
        'usage_date',
        'download_mb',
        'upload_mb',
        'total_mb',
        'session_duration_hours',
        'ip_address',
        'device_mac',
        'is_peak_hours',
    ];

    protected $casts = [
        'usage_date' => 'date',
        'session_duration_hours' => 'decimal:2',
        'is_peak_hours' => 'boolean',
    ];

    /**
     * Get the client that owns this usage record
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the subscription for this usage record
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Scope for current month usage
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('usage_date', now()->month)
                    ->whereYear('usage_date', now()->year);
    }

    /**
     * Scope for peak hours usage
     */
    public function scopePeakHours($query)
    {
        return $query->where('is_peak_hours', true);
    }

    /**
     * Get total usage in GB
     */
    public function getTotalGbAttribute()
    {
        return round($this->total_mb / 1024, 2);
    }

    /**
     * Get download usage in GB
     */
    public function getDownloadGbAttribute()
    {
        return round($this->download_mb / 1024, 2);
    }

    /**
     * Get upload usage in GB
     */
    public function getUploadGbAttribute()
    {
        return round($this->upload_mb / 1024, 2);
    }
}
