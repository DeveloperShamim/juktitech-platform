<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotPackage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_bn',
        'description',
        'price',
        'duration_days',
        'page_limit',
        'message_limit',
        'auto_reply_limit',
        'comment_automation_limit',
        'product_carousel',
        'analytics',
        'broadcast',
        'is_popular',
        'status',
        'sort_order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'product_carousel' => 'boolean',
        'analytics' => 'boolean',
        'broadcast' => 'boolean',
        'is_popular' => 'boolean',
        'status' => 'boolean',
        'price' => 'decimal:2',
    ];

    /**
     * Scope to get only active packages
     */
    public function scopeActive($query)
    {
        return $query->where('status', true)->orderBy('sort_order');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute()
    {
        return '৳' . number_format($this->price, 0);
    }

    /**
     * Get duration in human readable format
     */
    public function getDurationTextAttribute()
    {
        return match ((int) $this->duration_days) {
            7   => '৭ দিন (ট্রায়াল)',
            30  => '১ মাস',
            90  => '৩ মাস',
            180 => '৬ মাস',
            365 => '১ বছর',
            default => $this->duration_days . ' দিন',
        };
    }
}
