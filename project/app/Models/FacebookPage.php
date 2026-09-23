<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'page_id',
        'page_name',
        'page_access_token',
        'is_connected',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_connected' => 'boolean',
    ];

    /**
     * Get the user that owns the Facebook page.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all bot replies for this Facebook page.
     */
    public function botReplies()
    {
        return $this->hasMany(BotReply::class);
    }

    /**
     * Get all comment automations for this Facebook page.
     */
    public function commentAutomations()
    {
        return $this->hasMany(CommentAutomation::class);
    }

    /**
     * পেজ অ্যাক্সেস টোকেন এখনো Facebook-এ বৈধ কিনা।
     * true = valid, false = নিশ্চিত অবৈধ, null = যাচাই করা যায়নি (নেটওয়ার্ক/কনফিগ)
     */
    public function tokenStatus(): ?bool
    {
        $token = trim((string) $this->page_access_token);
        if ($token === '') {
            return false;
        }

        try {
            $setting = FacebookAppSetting::getSetting();
            if ($setting->isConfigured()) {
                $debug = \Illuminate\Support\Facades\Http::timeout(15)->get(
                    'https://graph.facebook.com/' . ($setting->graph_version ?: 'v18.0') . '/debug_token',
                    [
                        'input_token'  => $token,
                        'access_token' => $setting->app_id . '|' . $setting->app_secret,
                    ]
                );

                if ($debug->successful() && is_array($debug->json('data'))) {
                    return (bool) ($debug->json('data.is_valid') ?? false);
                }
            }

            // fallback: মেসেজিং টোকেনের জন্য /me
            $response = \Illuminate\Support\Facades\Http::timeout(12)
                ->get('https://graph.facebook.com/v18.0/me', [
                    'access_token' => $token,
                    'fields' => 'id,name',
                ]);

            if ($response->successful() && $response->json('id')) {
                return true;
            }

            $code = (int) ($response->json('error.code') ?? 0);
            if ($code === 190) {
                return false;
            }

            // অন্য এরর (পারমিশন ইত্যাদি) — নিশ্চিতভাবে অবৈধ বলা যাবে না
            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function tokenIsValid(): bool
    {
        return $this->tokenStatus() === true;
    }

    public function tokenIsDefinitelyInvalid(): bool
    {
        return $this->tokenStatus() === false;
    }
}
