<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'client_id',
        'subscription_id',
        'service_plan_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'paid_amount',
        'balance_due',
        'status',
        'description',
        'line_items',
        'notes',
        'sent_at',
        'paid_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'line_items' => 'array',
        'sent_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    /**
     * Get the client that owns the invoice
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the subscription for this invoice
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the service plan for this invoice
     */
    public function servicePlan()
    {
        return $this->belongsTo(ServicePlan::class);
    }

    /**
     * Get the payments for this invoice
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scope for overdue invoices
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
                    ->whereIn('status', ['sent', 'overdue']);
    }

    /**
     * Scope for unpaid invoices
     */
    public function scopeUnpaid($query)
    {
        return $query->whereIn('status', ['sent', 'overdue']);
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue()
    {
        return $this->due_date->isPast() && in_array($this->status, ['sent', 'overdue']);
    }

    /**
     * Check if invoice is fully paid
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Generate invoice number
     */
    public static function generateInvoiceNumber()
    {
        $lastInvoice = self::latest()->first();
        $number = $lastInvoice ? (int) substr($lastInvoice->invoice_number, 4) + 1 : 1;
        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
