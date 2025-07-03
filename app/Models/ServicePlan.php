<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServicePlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'monthly_price',
        'setup_fee',
        'speed_mbps',
        'data_limit_gb',
        'billing_cycle',
        'is_unlimited',
        'plan_type',
        'status',
        'features',
        'max_devices',
        'overage_rate',
    ];

    protected $casts = [
        'monthly_price' => 'decimal:2',
        'setup_fee' => 'decimal:2',
        'overage_rate' => 'decimal:4',
        'is_unlimited' => 'boolean',
        'features' => 'array',
    ];

    /**
     * Get the subscriptions for this plan
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get active subscriptions for this plan
     */
    public function activeSubscriptions()
    {
        return $this->subscriptions()->where('status', 'active');
    }

    /**
     * Scope for active plans
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for plans by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('plan_type', $type);
    }

    /**
     * Get formatted speed display
     */
    public function getFormattedSpeedAttribute()
    {
        return $this->speed_mbps . ' Mbps';
    }

    /**
     * Get formatted data limit display
     */
    public function getFormattedDataLimitAttribute()
    {
        return $this->is_unlimited ? 'Unlimited' : $this->data_limit_gb . ' GB';
    }
}
