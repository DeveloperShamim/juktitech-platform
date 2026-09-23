<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    // ১. fillable অ্যারে আপডেট করা হয়েছে (Website Collection & SEO ফিল্ডসহ)
    protected $fillable = [
        'name', 
        'slug', 
        'photo', 
        'price', 
        'category_key',
        'category_name',
        'badge_text',
        'badge_class',
        'features',
        'license_type',
        'sort_order',
        'description', 
        'details', 
        'demo_link', 
        'demo_admin_link',
        'demo_admin_email',
        'demo_admin_password',
        'pricing_tiers',
        'purchase_link', 
        'tutorials',
        'status',
        'is_digital',
        'google_drive_file_id',
        'digital_file_name',
        'download_limit',
        'meta_title', 
        'meta_keyword', 
        'meta_description'
    ];

    // ২. ডাটা টাইপ কাস্টিং
    protected $casts = [
        'status'         => 'boolean',
        'is_digital'     => 'boolean',
        'price'          => 'double',
        'download_limit' => 'integer',
        'tutorials'      => 'array',
        'features'       => 'array',
        'pricing_tiers'  => 'array',
        'sort_order'     => 'integer',
    ];

    public function getCleanPricingTiersAttribute(): array
    {
        $tiers = $this->pricing_tiers;
        if (is_string($tiers)) {
            $decoded = json_decode($tiers, true);
            $tiers = is_array($decoded) ? $decoded : [];
        }

        if (is_array($tiers) && !empty($tiers)) {
            return $tiers;
        }

        // ডিফল্ট সমৃদ্ধ ৩-টিয়ার প্যাকেজ কাঠামো (রেফারেন্স স্ক্রিনশট অনুযায়ী)
        $basePrice = $this->price > 0 ? $this->price : 8500;
        $t1Price = $basePrice;
        $t1Prev = round($t1Price * 1.8);
        $t1Save = $t1Prev - $t1Price;

        $t2Price = round($basePrice * 1.7);
        $t2Prev = round($t2Price * 1.75);
        $t2Save = $t2Prev - $t2Price;

        $t3Price = 25000;
        $t3Prev = 50000;
        $t3Save = 25000;

        return [
            [
                'tier_key' => 'source_code',
                'title' => 'সম্পূর্ণ সোর্স কোড',
                'subtitle' => 'লাইভ ডেমো সাইটের মতো সেম-টু-সেম ফুল ওয়েবসাইট, ৫০+ রেডি ফিচার ও ক্লিন কোডবেস।',
                'badge' => 'সেলফ-হোস্টিং ও ডেভেলপার',
                'badge_color' => 'neutral',
                'highlight_tag' => '৫০+ ফিচার রেডি',
                'highlight_sub' => 'ডেমো সাইটের মতো সেম-টু-সেম ওয়েবসাইট ও ডেমো কনটেন্ট অন্তর্ভুক্ত',
                'price' => $t1Price,
                'previous_price' => $t1Prev,
                'savings_text' => '৳ ' . number_format($t1Save, 0) . ' সাশ্রয়',
                'btn_text' => 'সোর্স কোড কিনুন',
                'features' => [
                    'সম্পূর্ণ কাস্টমাইজেবল (Fully Customizable) ও ফুল সোর্স কোড এক্সেস',
                    'আনলিমিটেড ডোমেইন লাইসেন্স — নিজের ও ক্লায়েন্ট প্রজেক্টে ব্যবহারযোগ্য',
                    'সম্পূর্ণ Laravel 11 + React 19 আনএনক্রিপ্টেড কোড',
                    'ভিডিও ইনস্টলেশন গাইড, ডাটাবেস স্কিমা ও ডেমো সিডার',
                    'Steadfast & Pathao কুরিয়ার অটোমেটেড API ইন্টিগ্রেশন',
                    'স্মার্ট ফ্রড ডিফেন্স ও ১-ক্লিক ক্যাশ অন ডেলিভারি (COD)',
                    'যেকোনো cPanel বা VPS সার্ভারে ইনস্টল উপযোগী',
                    'আজীবন লাইসেন্স — কোনো মাসিক বা বাৎসরিক চার্জ নেই'
                ]
            ],
            [
                'tier_key' => 'branding_setup',
                'title' => 'সোর্স কোড + ব্র্যান্ডিং',
                'subtitle' => 'ডেমো সাইটের মতো সেম-টু-সেম রেডি ওয়েবসাইট আপনার নিজস্ব ব্র্যান্ড কালার, লোগো ও সার্ভারে ফুল লাইভ সেটআপ।',
                'badge' => '🔥 সর্বাধিক জনপ্রিয়',
                'badge_color' => 'hot',
                'is_featured' => true,
                'highlight_tag' => '★ সম্পূর্ণ রেডি সেটআপ',
                'highlight_sub' => 'সোর্স কোডের সকল ফিচার + ডেমো কনটেন্ট সহ সম্পূর্ণ রেডি স্টোর',
                'price' => $t2Price,
                'previous_price' => $t2Prev,
                'savings_text' => '৳ ' . number_format($t2Save, 0) . ' সাশ্রয়',
                'btn_text' => 'ব্র্যান্ডিং সহ নিন',
                'features' => [
                    'সম্পূর্ণ কাস্টমাইজেবল ও ফুল এক্সেস — আনলিমিটেড ডোমেইন (নিজের ও ক্লায়েন্ট প্রজেক্ট)',
                    'সম্পূর্ণ সোর্স কোড ও ৫০+ রেডি ফিচারের সবকিছু অন্তর্ভুক্ত',
                    'লাইভ ডেমো সাইটের মতো সেম-টু-সেম ওয়েবসাইট ও ডেমো কনটেন্ট সেটআপ',
                    'আপনার নিজস্ব ব্র্যান্ড কালার, লোগো ও প্রিমিয়াম ব্যানার ডিজাইন',
                    'প্রোজেস বিশেষজ্ঞ টিম দ্বারা VPS বা cPanel-এ ফুল লাইভ সেটআপ',
                    'Steadfast ও Pathao কুরিয়ার + ১-ক্লিক চেকআউট কনফিগারেশন',
                    'bKash, Nagad ও কার্ড পেমেন্ট গেটওয়ে লাইভ ইন্টিগ্রেশন',
                    'ফেসবুক পিক্সেল, কনভার্শন API ও ফ্রি SSL অপ্টিমাইজেশন',
                    'আজীবন লাইসেন্স ও প্রায়োরিটি ১-টু-১ ভিআইপি হোয়াটসঅ্যাপ সাপোর্ট'
                ]
            ],
            [
                'tier_key' => 'custom_dev',
                'title' => 'কাস্টম ডেভেলপমেন্ট',
                'subtitle' => 'আপনার ব্র্যান্ডের স্পেসিফিকেশন অনুযায়ী স্ক্র্যাচ থেকে সম্পূর্ণ কাস্টম ওয়েবসাইট ও বিশেষ আর্কিটেকচার।',
                'badge' => 'এন্টারপ্রাইজ সলিউশন',
                'badge_color' => 'enterprise',
                'highlight_tag' => '★ কাস্টম বিল্ড',
                'highlight_sub' => 'সম্পূর্ণ ইউনিক আর্কিটেকচার ও ডেডিকেটেড ফিচার ডেভেলপমেন্ট',
                'price' => $t3Price,
                'previous_price' => $t3Prev,
                'savings_text' => '৳ ' . number_format($t3Save, 0) . ' সাশ্রয়',
                'btn_text' => 'কাস্টম প্রজেক্ট শুরু করুন',
                'features' => [
                    '১০০% কাস্টমাইজেবল ও ফুল এক্সেস — আনলিমিটেড ডোমেইন ও কমার্শিয়াল লাইসেন্স',
                    'আপনার ব্র্যান্ডের জন্য ইউনিক UI/UX, ব্যানার ও ব্র্যান্ড আইডেন্টিটি',
                    'যেকোনো কাস্টম মডিউল ও থার্ড-পার্টি API ইন্টিগ্রেশন সুবিধা',
                    'কাস্টম POS, ERP অথবা ইনভেন্টরি ম্যানেজমেন্ট সিস্টেম লিংক',
                    'কমপ্লিট সিকিউরিটি পেনিট্রেশন টেস্ট ও লোড পারফরম্যান্স অডিট',
                    'হাই-ট্রাফিকের জন্য ডেডিকেটেড সার্ভার ও ডাটাবেস অপ্টিমাইজেশন',
                    'ডেডিকেটেড সিনিয়র সফটওয়্যার ইঞ্জিনিয়ার ও আজীবন ভিআইপি সাপোর্ট'
                ]
            ]
        ];
    }

    public function getCleanFeaturesAttribute(): array
    {
        $features = $this->features;
        if (is_string($features)) {
            $decoded = json_decode($features, true);
            $features = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($features) || empty($features)) {
            return [
                '১০০% রেসপনসিভ ও ইউনিক ডিজাইন',
                'সুপারফাস্ট লোডিং ও এসইও কাঠামো',
                'অ্যাডভান্সড অ্যাডমিন প্যানেল কন্ট্রোল',
                'লাইফটাইম আপডেট ও ফুল সাপোর্ট'
            ];
        }

        return array_values(array_filter(array_map('trim', $features)));
    }

    public function orders()
    {
        return $this->hasMany(ProductOrder::class);
    }

    public function isDigitalProduct(): bool
    {
        return (bool) $this->is_digital;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function tutorialEmbedUrl(string $url): ?string
    {
        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = trim((string) ($parts['path'] ?? ''), '/');
        parse_str((string) ($parts['query'] ?? ''), $query);

        $videoId = null;

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = explode('/', $path)[0] ?? null;
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com'], true)) {
            if ($path === 'watch') {
                $videoId = $query['v'] ?? null;
            } elseif (preg_match('#^(?:embed|shorts)/([^/]+)#', $path, $matches)) {
                $videoId = $matches[1];
            }
        }

        if (!is_string($videoId) || !preg_match('/^[A-Za-z0-9_-]{6,20}$/', $videoId)) {
            return null;
        }

        return 'https://www.youtube.com/embed/' . $videoId;
    }

    public function tutorialVideoId(string $url): ?string
    {
        $parts = parse_url($url);
        $host = strtolower((string) ($parts['host'] ?? ''));
        $path = trim((string) ($parts['path'] ?? ''), '/');
        parse_str((string) ($parts['query'] ?? ''), $query);

        if (in_array($host, ['youtu.be', 'www.youtu.be'], true)) {
            $videoId = explode('/', $path)[0] ?? null;
        } elseif (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com', 'music.youtube.com'], true)) {
            if ($path === 'watch') {
                $videoId = $query['v'] ?? null;
            } elseif (preg_match('#^(?:embed|shorts)/([^/]+)#', $path, $matches)) {
                $videoId = $matches[1];
            } else {
                $videoId = null;
            }
        } else {
            $videoId = null;
        }

        return is_string($videoId) && preg_match('/^[A-Za-z0-9_-]{6,20}$/', $videoId)
            ? $videoId
            : null;
    }

    public function tutorialThumbnailUrl(?string $videoId): ?string
    {
        if (!$videoId) {
            return null;
        }

        return 'https://img.youtube.com/vi/' . $videoId . '/hqdefault.jpg';
    }

    // ৩. অটো স্লাগ তৈরির লজিক
    protected static function boot() {
        parent::boot();

        static::creating(function ($product) {
            // যদি কন্ট্রোলার থেকে স্লাগ না আসে, তবেই নাম থেকে অটো জেনারেট হবে
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });

        static::updating(function ($product) {
             // আপডেটের সময় নাম পরিবর্তন হলে এবং স্লাগ ফাঁকা থাকলে নতুন স্লাগ তৈরি হবে
             if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }
}