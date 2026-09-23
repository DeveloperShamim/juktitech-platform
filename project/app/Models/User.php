<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'username',
        'photo',
        'zip',
        'residency',
        'city',
        'address',
        'phone',
        'fax',
        'email',
        'designation',
        'password',
        'verification_link',
        'affilate_code',
        'is_provider',
        'twofa',
        'go',
        'details',
        'api_key', // ফ্রড চেকিং এবং এসএমএস এপিআই (উভয় কাজেই ব্যবহার হবে)

        // [নতুন] SMS Reseller System এর জন্য যুক্ত করা হলো
        'wallet_balance',     // কাস্টমারের বর্তমান ব্যালেন্স
        'non_masking_rate',   // নন-মাস্কিং এসএমএস রেট
        'masking_rate',       // মাস্কিং এসএমএস রেট

        // [নতুন] Facebook Chatbot Subscription এর জন্য যুক্ত করা হলো
        'active_plan',              // Active chatbot plan name
        'plan_expiry',              // Chatbot plan expiry date
        'connected_pages_limit',    // Maximum Facebook pages allowed
        'monthly_message_limit',    // Monthly message limit
        'messages_sent_this_month', // Messages sent counter

        // [নতুন] লাইসেন্স জেনারেশন অনুমতি
        'free_license_generation', // Admin দ্বারা বিনামূল্যে লাইসেন্স জেনারেট করার অনুমতি
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'plan_expiry' => 'datetime',
    ];

    public function customer()
    {
        // এটি ধরে নিচ্ছে যে customers টেবিলে user_id নামে একটি কলাম আছে
        return $this->hasOne(Customer::class, 'user_id', 'id');
    }

    public function posts(){
        return $this->hasMany('App\Models\Post','user_id');
    }

    /**
     * Get all Facebook pages for this user.
     */
    public function facebookPages()
    {
        return $this->hasMany(FacebookPage::class);
    }

    /**
     * Check if user has active chatbot subscription
     * 
     * @return bool
     */
    public function hasActiveChatbotPlan()
    {
        if (empty($this->active_plan)) {
            return false;
        }

        if ($this->plan_expiry && \Carbon\Carbon::parse($this->plan_expiry)->isPast()) {
            return false;
        }

        return true;
    }

    /**
     * Get remaining days in chatbot subscription
     * 
     * @return int
     */
    public function getChatbotDaysRemaining()
    {
        if (!$this->plan_expiry) {
            return 0;
        }

        $expiry = \Carbon\Carbon::parse($this->plan_expiry);
        
        if ($expiry->isPast()) {
            return 0;
        }

        return now()->diffInDays($expiry);
    }

    /**
     * Check if user can connect more Facebook pages
     * 
     * @return bool
     */
    public function canConnectMorePages()
    {
        $connectedPages = $this->facebookPages()->where('is_connected', true)->count();
        $limit = $this->connected_pages_limit ?? 1;

        return $connectedPages < $limit;
    }

    /**
     * এপিআই কি জেনারেট করার ফাংশন
     * (এটি আগের মতোই থাকবে)
     */
    public function generateApiKey()
    {
        $this->api_key = \Illuminate\Support\Str::random(60);
        $this->save();
        return $this->api_key;
    }
}