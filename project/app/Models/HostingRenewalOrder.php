<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostingRenewalOrder extends Model
{
    protected $table = 'hosting_renewal_orders';

    protected $fillable = [
        'user_id',
        'user_hosting_id',
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

    public function userHosting()
    {
        return $this->belongsTo(UserHosting::class, 'user_hosting_id');
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
     * অর্ডার পেইড মার্ক করে হোস্টিং রিনিউ (মাসিক হলে +১ মাস, নইলে +১ বছর)
     * WHM আনসাস্পেন্ড Admin controller থেকে আলাদা কল করতে হবে।
     */
    public function markAsPaidAndRenewHosting(): bool
    {
        if (!$this->isPending()) {
            return false;
        }
        $hosting = $this->userHosting;
        $newDue = $hosting
            ? \App\Services\DomainHostingPricingService::renewNextDueDate($hosting)
            : \Carbon\Carbon::now()->addYear();
        \Illuminate\Support\Facades\DB::transaction(function () use ($newDue) {
            $this->update([
                'status'  => 'paid',
                'paid_at'  => \Carbon\Carbon::now(),
            ]);
            $data = [
                'status'        => 'Active',
                'next_due_date' => $newDue,
            ];
            if (\Illuminate\Support\Facades\Schema::hasColumn('user_hostings', 'suspended_at')) {
                $data['suspended_at'] = null;
            }
            $this->userHosting->update($data);
        });
        return true;
    }
}
