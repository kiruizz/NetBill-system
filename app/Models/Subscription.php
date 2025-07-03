<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'client_subscriptions'; // Use different table name

    protected $fillable = [
        'client_id',
        'service_plan_id',
        'start_date',
        'end_date',
        'status',
        'monthly_amount',
        'next_billing_date',
        'last_billing_date',
        'installation_notes',
        'custom_settings',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'next_billing_date' => 'date',
        'last_billing_date' => 'date',
        'monthly_amount' => 'decimal:2',
        'custom_settings' => 'array',
    ];

    /**
     * Get the client that owns the subscription
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the service plan for this subscription
     */
    public function servicePlan()
    {
        return $this->belongsTo(ServicePlan::class);
    }

    /**
     * Get the invoices for this subscription
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the network usages for this subscription
     */
    public function networkUsages()
    {
        return $this->hasMany(NetworkUsage::class);
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for subscriptions due for billing
     */
    public function scopeDueForBilling($query)
    {
        return $query->where('status', 'active')
                    ->where('next_billing_date', '<=', now()->toDateString());
    }

    /**
     * Check if subscription is active
     */
    public function isActive()
    {
        return $this->status === 'active';
    }

    /**
     * Get current month usage
     */
    public function getCurrentMonthUsage()
    {
        return $this->networkUsages()
                   ->whereMonth('usage_date', now()->month)
                   ->whereYear('usage_date', now()->year)
                   ->sum('total_mb');
    }
}
