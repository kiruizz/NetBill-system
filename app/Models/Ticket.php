<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'client_id',
        'subject',
        'description',
        'priority',
        'status',
        'category',
        'assigned_to',
        'created_by',
        'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the client that owns the ticket
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the user assigned to this ticket
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created this ticket
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['open', 'in_progress']);
    }

    /**
     * Scope for high priority tickets
     */
    public function scopeHighPriority($query)
    {
        return $query->whereIn('priority', ['high', 'urgent']);
    }

    /**
     * Generate ticket number
     */
    public static function generateTicketNumber()
    {
        $lastTicket = self::latest()->first();
        $number = $lastTicket ? (int) substr($lastTicket->ticket_number, 4) + 1 : 1;
        return 'TKT-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Check if ticket is resolved
     */
    public function isResolved()
    {
        return $this->status === 'resolved';
    }
}
