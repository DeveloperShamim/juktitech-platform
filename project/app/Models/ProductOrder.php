<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOrder extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'install_domain',
        'license_id',
        'invoice_id',
        'amount',
        'status',
        'download_token',
        'download_count',
        'download_limit',
        'paid_at',
    ];

    protected $casts = [
        'amount'          => 'decimal:2',
        'download_count'  => 'integer',
        'download_limit'  => 'integer',
        'paid_at'         => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function license(): BelongsTo
    {
        return $this->belongsTo(License::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function downloadsRemaining(): int
    {
        return max(0, $this->download_limit - $this->download_count);
    }

    public function canDownload(): bool
    {
        return $this->isPaid() && $this->downloadsRemaining() > 0;
    }

    public function downloadUrl(): string
    {
        return route('digital-product.download', ['token' => $this->download_token]);
    }
}
