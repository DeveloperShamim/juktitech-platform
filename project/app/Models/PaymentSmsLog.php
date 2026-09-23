<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentSmsLog extends Model
{
    protected $fillable = [
        'provider',
        'transaction_id',
        'amount',
        'sender_number',
        'raw_message',
        'sms_from',
        'received_at',
        'status',
        'payment_transaction_id',
        'matched_at',
    ];

    protected $casts = [
        'amount'      => 'decimal:2',
        'received_at' => 'datetime',
        'matched_at'  => 'datetime',
    ];

    public function paymentTransaction(): BelongsTo
    {
        return $this->belongsTo(PaymentTransaction::class, 'payment_transaction_id');
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }
}
