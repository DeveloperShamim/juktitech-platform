<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserChatbotProduct extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'image',
        'product_url',
        'sort_order',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'status' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
