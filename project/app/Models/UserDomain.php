<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User; // User মডেল ইম্পোর্ট করা হলো

class UserDomain extends Model
{
    protected $guarded = [];

    protected $casts = [
        'registration_date' => 'date',
        'next_due_date'     => 'date',
        'billing_years'     => 'integer',
        'recurring_amount'  => 'float',
    ];

    // এই ফাংশনটি যোগ করুন
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // আপনার যদি অর্ডারের সাথেও রিলেশন লাগে, তবে এটিও রাখতে পারেন (অপশনাল)
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}