<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DomainRenewalOrder extends Model
{
    protected $table = 'domain_renewal_orders';

    protected $fillable = [
        'user_id',
        'user_domain_id',
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

    public function userDomain()
    {
        return $this->belongsTo(UserDomain::class, 'user_domain_id');
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
     * অর্ডার পেইড মার্ক করে ডোমেইন রিনিউ (next_due_date + ১ বছর, status = Active)
     */
    public function markAsPaidAndRenewDomain(): bool
    {
        if (!$this->isPending()) {
            return false;
        }
        $newDue = \Carbon\Carbon::now()->addYear();
        \Illuminate\Support\Facades\DB::transaction(function () use ($newDue) {
            $this->update([
                'status'  => 'paid',
                'paid_at' => \Carbon\Carbon::now(),
            ]);
            $this->userDomain->update([
                'status'        => 'Active',
                'next_due_date' => $newDue,
            ]);
        });
        return true;
    }
}
