<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'payment_reference',
        'client_id',
        'invoice_id',
        'amount',
        'payment_method',
        'transaction_id',
        'status',
        'payment_date',
        'description',
        'notes',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the client that made the payment
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the invoice this payment is for
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the user who processed the payment
     */
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    /**
     * Scope for completed payments
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending payments
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Generate payment reference
     */
    public static function generatePaymentReference()
    {
        $lastPayment = self::latest()->first();
        $number = $lastPayment ? (int) substr($lastPayment->payment_reference, 4) + 1 : 1;
        return 'PAY-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}
