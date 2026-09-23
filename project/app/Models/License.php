<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    /**
     * ডাটাবেস টেবিল নেম (যদি ডিফল্ট 'licenses' না হয়ে অন্য কিছু হয় তবে এটি প্রয়োজন)
     */
    protected $table = 'licenses';

    /**
     * Mass Assignment এর জন্য অনুমোদিত ফিল্ডসমূহ।
     * 'user_id' এখানে থাকা বাধ্যতামূলক যাতে ইউজার সিলেক্ট করলে সেটি সেভ হয়।
     */
    protected $fillable = [
        'user_id', 
        'domain_name', 
        'license_key',
        'signature_key',
        'site_path',
        'validity_years', 
        'expiry_date', 
        'status'
    ];

    /**
     * তারিখ ফরম্যাট ম্যানেজমেন্ট।
     * এটি expiry_date কলামকে কার্বন অবজেক্টে রূপান্তর করবে।
     */
    protected $dates = ['expiry_date'];

    /**
     * Lifetime লাইসেন্স কিনা চেক করে (expiry_date null মানে Lifetime)
     */
    public function isLifetime(): bool
    {
        return $this->expiry_date === null;
    }

    /**
     * ডিফল্ট ডাটা টাইপ কাস্টিং।
     */
    protected $casts = [
        'status' => 'integer',
        'expiry_date' => 'datetime',
    ];

    /**
     * ইউজারের সাথে রিলেশনশিপ (Belongs To)।
     * এই মেথডটির মাধ্যমেই ডাটাটেবিলে $row->user->name দেখা সম্ভব হয়।
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * API/ক্লায়েন্ট ডোমেইন স্ট্রিং নরমালাইজ (verify-license / check-update)।
     */
    public static function normalizeHostInput($value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $value = preg_replace('#^https?://#i', '', $value);
        $value = preg_replace('#^www\.#i', '', $value);
        $value = explode('/', $value)[0];

        return strtolower(trim($value));
    }

    /**
     * নরমালাইজড ডোমেইন + কী দিয়ে লাইসেন্স খুঁজে (ডাটাবেসে www/URL/কেস ভিন্ন হলেও মিলবে)।
     */
    public static function findForApiVerification(string $normalizedDomain, string $licenseKey): ?self
    {
        $key = trim($licenseKey);
        if ($key === '' || $normalizedDomain === '') {
            return null;
        }

        $variants = array_values(array_unique(array_filter([
            $key,
            strtoupper($key),
            strtolower($key),
        ])));

        $candidates = static::query()->whereIn('license_key', $variants)->get();
        foreach ($candidates as $row) {
            if (static::normalizeHostInput($row->domain_name) === $normalizedDomain) {
                return $row;
            }
        }

        return null;
    }

    /** verify / check-update এ সক্রিয় স্ট্যাটাস */
    public static function isActiveForApi(self $license): bool
    {
        $status = $license->status;
        if ($status === 'active' || $status === 'Active') {
            return true;
        }

        return (int) $status === 1;
    }
}