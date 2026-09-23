<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'invoice_id',
        'full_name',
        'email',
        'amount',
        'currency',
        'metadata',
        'redirect_url',
        'cancel_url',
        'webhook_url',
        'status',
        'gateway_provider',
        'gateway_invoice_id',
        'payment_method',
        'sender_number',
        'transaction_id',
        'admin_note',
        'paid_at',
        'webhook_sent_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'webhook_sent_at' => 'datetime',
    ];

    public function isPaid(): bool
    {
        return $this->status === 'completed';
    }

    public function verifyStatus(): string
    {
        return match ($this->status) {
            'completed' => 'COMPLETED',
            'failed' => 'FAILED',
            'cancelled' => 'CANCELLED',
            default => 'PENDING',
        };
    }
}
