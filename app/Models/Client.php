<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'national_id',
        'address',
        'location',
        'status',
        'client_type',
        'account_balance',
        'connection_date',
        'notes',
    ];

    protected $casts = [
        'connection_date' => 'date',
        'account_balance' => 'decimal:2',
    ];

    /**
     * Get the client's subscriptions
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get the client's active subscription
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)->where('status', 'active');
    }

    /**
     * Get the client's devices
     */
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    /**
     * Get the client's invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the client's payments through invoices
     */
    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Invoice::class);
    }

    /**
     * Get the client's tickets
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Get the client's network usage
     */
    public function networkUsages()
    {
        return $this->hasMany(NetworkUsage::class);
    }

    /**
     * Get unpaid invoices
     */
    public function unpaidInvoices()
    {
        return $this->invoices()->whereIn('status', ['pending', 'partially_paid', 'overdue']);
    }

    /**
     * Calculate total outstanding balance
     */
    public function getTotalOutstandingAttribute()
    {
        return $this->unpaidInvoices()->sum('total_amount') - $this->payments()->sum('amount');
    }
}
