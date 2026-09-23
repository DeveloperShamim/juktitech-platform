<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class FacebookAppSetting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'app_id',
        'app_secret',
        'verify_token',
        'graph_version',
        'webhook_url',
        'redirect_url',
        'privacy_policy_url',
        'terms_of_service_url',
        'data_deletion_url',
        'is_connected',
        'page_access_token',
        'connected_at',
        'webhook_fields',
        'permissions',
        'error_log',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_connected' => 'boolean',
        'connected_at' => 'datetime',
        'webhook_fields' => 'array',
        'permissions' => 'array',
    ];

    /**
     * Get the singleton instance (only one setting record should exist)
     */
    public static function getSetting()
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'app_id' => null,
                'app_secret' => null,
                'verify_token' => null,
                'graph_version' => 'v18.0',
                'is_connected' => false,
            ]
        );
    }

    /**
     * Set encrypted app secret
     */
    public function setAppSecretAttribute($value)
    {
        if ($value) {
            $this->attributes['app_secret'] = Crypt::encryptString($value);
        }
    }

    /**
     * Get decrypted app secret
     */
    public function getAppSecretAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return $value; // Return as-is if decryption fails
            }
        }
        return null;
    }

    /**
     * Get webhook URL
     */
    public function getWebhookUrlAttribute($value)
    {
        return $value ?? route('chatbot.webhook.handle');
    }

    /**
     * Check if app is properly configured
     */
    public function isConfigured()
    {
        return !empty($this->app_id) && !empty($this->app_secret) && !empty($this->verify_token);
    }

    /**
     * Get connection status badge
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->is_connected) {
            return '<span class="badge badge-success">Connected</span>';
        } elseif ($this->isConfigured()) {
            return '<span class="badge badge-warning">Configured</span>';
        } else {
            return '<span class="badge badge-secondary">Not Configured</span>';
        }
    }
}
