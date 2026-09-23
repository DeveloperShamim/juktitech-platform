<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LicenseRenewalOrder extends Model
{
    protected $table = 'license_renewal_orders';

    protected $fillable = [
        'user_id',
        'license_id',
        'amount',
        'invoice_id',
        'status',
        'paid_at',
        'notes',
    ];

    protected $casts = [
        'amount'   => 'decimal:2',
        'paid_at'  => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * অর্ডার পেইড মার্ক করে লাইসেন্স রিনিউ (অ্যাডমিন থেকে ম্যানুয়াল পেইড করলে)
     */
    public function markAsPaidAndRenewLicense(): bool
    {
        if (!$this->isPending()) {
            return false;
        }
        $newExpiry = \Carbon\Carbon::now()->addYear();
        \Illuminate\Support\Facades\DB::transaction(function () use ($newExpiry) {
            $this->update([
                'status'  => 'paid',
                'paid_at' => \Carbon\Carbon::now(),
            ]);
            $this->license->update([
                'status'      => 1,
                'expiry_date' => $newExpiry,
            ]);
        });
        return true;
    }
}
