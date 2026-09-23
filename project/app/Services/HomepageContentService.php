<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class HomepageContentService
{
    public function hero(): object
    {
        $row = DB::table('about_section_contents')->where('id', 1)->first();
        $defaults = $this->defaultHeroData();

        if (!$row) {
            return (object) [
                'title' => null,
                'subtitle' => null,
                'image' => null,
                'hero_badge' => $defaults['badge'],
                'hero_mockup_image' => null,
                'stats' => $defaults['stats'],
                'buttons' => $defaults['buttons'],
                'mockup' => $defaults['mockup'],
            ];
        }

        $heroData = $this->decodeJson($row->hero_data ?? null);

        return (object) [
            'title' => $row->title ?? null,
            'subtitle' => $row->subtitle ?? null,
            'image' => $row->image ?? null,
            'hero_badge' => $row->hero_badge ?? $defaults['badge'],
            'hero_mockup_image' => $row->hero_mockup_image ?? null,
            'stats' => $heroData['stats'] ?? $defaults['stats'],
            'buttons' => $heroData['buttons'] ?? $defaults['buttons'],
            'mockup' => array_merge($defaults['mockup'], $heroData['mockup'] ?? []),
        ];
    }

    public function sections(): array
    {
        $defaults = $this->defaultSections();
        $rows = DB::table('section_contents')->get()->keyBy('section_key');

        $sections = [];
        foreach ($defaults as $key => $default) {
            $row = $rows->get($key);
            $extra = $this->decodeJson($row->extra_json ?? null);

            $sections[$key] = (object) [
                'badge' => $row->badge ?? $default['badge'] ?? null,
                'title' => $row->title ?? $default['title'],
                'subtitle' => $row->subtitle ?? ($default['subtitle'] ?? null),
                'description' => $row->description ?? $row->subtitle ?? ($default['description'] ?? null),
                'extra' => array_merge($default['extra'] ?? [], $extra),
            ];
        }

        return $sections;
    }

    public function section(string $key): object
    {
        return $this->sections()[$key] ?? (object) $this->defaultSections()[$key];
    }

    public function defaultHeroData(): array
    {
        return [
            'badge' => '৫০০+ ওয়েবসাইট সফলভাবে ডেলিভারি',
            'stats' => [
                ['icon' => 'fas fa-laptop-code', 'value' => '৫০০+', 'label' => 'ওয়েবসাইট ডেলিভারি'],
                ['icon' => 'fas fa-smile', 'value' => '১০০০+', 'label' => 'সন্তুষ্ট ক্লায়েন্ট'],
                ['icon' => 'fas fa-headset', 'value' => '২৪/৭', 'label' => 'সাপোর্ট সেবা'],
            ],
            'buttons' => [
                ['text' => 'প্যাকেজ দেখুন', 'url' => '/priceing', 'style' => 'primary', 'icon' => 'fas fa-search'],
                ['text' => 'ফ্রি কনসালটেন্সি', 'url' => '/freeconsultant', 'style' => 'secondary', 'icon' => 'fas fa-arrow-right'],
            ],
            'mockup' => [
                'type' => 'wireframe',
                'float_1' => 'Secure Payment',
                'float_2' => 'Fast Delivery',
                'float_3' => 'COD Available',
                'browser_url' => 'shop.yourstore.com',
                'search_placeholder' => 'প্রোডাক্ট খুঁজুন...',
                'cart_count' => '3',
                'sale_text' => '৫০% OFF',
                'banner_btn' => 'Shop Now',
                'categories' => ['All', 'Electronics', 'Fashion', 'Home'],
                'products' => [
                    ['badge' => 'Hot', 'badge_class' => '', 'price' => '৳ ১,২৯৯', 'rating' => '4.8'],
                    ['badge' => '', 'badge_class' => '', 'price' => '৳ ৮৯৯', 'rating' => '4.6'],
                    ['badge' => 'New', 'badge_class' => 'is-new', 'price' => '৳ ২,৪৯৯', 'rating' => '5.0'],
                ],
                'checkout_text' => 'কার্ট: ৩ আইটেম · ৳ ৪,৫৯৭',
                'checkout_btn' => 'Checkout',
            ],
        ];
    }

    public function defaultSections(): array
    {
        return [
            'service_section' => [
                'badge' => 'আমাদের সেবা',
                'title' => 'আমরা কিভাবে <span class="home-services__accent">সাহায্য করি?</span>',
                'description' => 'ওয়েব ডিজাইন, সফটওয়্যার, SEO ও ডোমেইন-হোস্টিং — আপনার ব্যবসার ডিজিটাল গ্রোথের জন্য প্রয়োজনীয় সব সেবা এক জায়গায়।',
                'extra' => [
                    'highlights' => [
                        ['icon' => 'fas fa-bolt', 'text' => 'দ্রুত ডেলিভারি'],
                        ['icon' => 'fas fa-comments', 'text' => 'ফ্রি কনসালটেন্সি'],
                        ['icon' => 'fas fa-headset', 'text' => '২৪/৭ সাপোর্ট'],
                        ['icon' => 'fas fa-users', 'text' => '১০০০+ ক্লায়েন্ট'],
                    ],
                ],
            ],
            'pricing_section' => [
                'badge' => 'আমাদের প্যাকেজ',
                'title' => 'বাজেট অনুযায়ী <span class="home-pricing__accent">সেরা প্ল্যান</span> বেছে নিন',
                'description' => 'কোনো গোপন চার্জ নেই। আপনার ব্যবসার প্রয়োজন অনুযায়ী প্যাকেজ সিলেক্ট করুন এবং আজই শুরু করুন।',
                'extra' => [],
            ],
            'choose_section' => [
                'badge' => 'কেন আমরা',
                'title' => 'কেন আমাদেরই <span class="home-choose__accent">বেছে নিবেন?</span>',
                'description' => 'অভিজ্ঞ টিম, সময়মত ডেলিভারি ও সাশ্রয়ী মূল্য — আপনার ব্যবসার ডিজিটাল সফলতার জন্য আমরা পাশে আছি।',
                'extra' => [],
            ],
            'portfolio_section' => [
                'badge' => 'পোর্টফোলিও',
                'title' => 'আমাদের <span class="home-portfolio__accent">সাম্প্রতিক কাজসমূহ</span>',
                'description' => 'ওয়েবসাইট, ই-কমার্স ও সফটওয়্যার প্রজেক্ট — আমাদের তৈরি করা কিছু সাম্প্রতিক কাজ একনজরে দেখুন।',
                'extra' => ['filter_all' => 'সকল', 'more_text' => 'সব পোর্টফোলিও দেখুন'],
            ],
            'reviews_section' => [
                'badge' => 'প্রশংসা সমূহ',
                'title' => 'আমাদের ক্লায়েন্টরা <span class="home-reviews__accent">কি বলে?</span>',
                'description' => 'আমাদের সেবায় সন্তুষ্ট ক্লায়েন্টদের মূল্যবান মতামত — আপনার বিশ্বাসই আমাদের অনুপ্রেরণা।',
                'extra' => [],
            ],
            'steps_section' => [
                'badge' => 'সহজ প্রক্রিয়া',
                'title' => 'মাত্র <span class="home-steps__accent">৩ টি ধাপে</span> শুরু করুন',
                'description' => 'কোনো জটিলতা নেই। ফ্রি কনসালটেন্সি নিন এবং আজই আপনার প্রজেক্ট শুরু করুন।',
                'extra' => [
                    'steps' => [
                        ['num' => '১', 'icon' => 'fas fa-comments', 'color' => 'is-blue', 'title' => 'ফ্রি কনসালটেন্সি', 'desc' => 'আমাদের সাথে কথা বলুন এবং আপনার ব্যবসার প্রয়োজন আমাদের জানান।'],
                        ['num' => '২', 'icon' => 'fas fa-laptop-code', 'color' => 'is-purple', 'title' => 'ডিজাইন ও ডেভেলপমেন্ট', 'desc' => 'আমরা আপনার ওয়েবসাইট ডিজাইন, ডেভেলপ ও টেস্ট করে প্রস্তুত করি।'],
                        ['num' => '৩', 'icon' => 'fas fa-rocket', 'color' => 'is-green', 'title' => 'লঞ্চ ও সাপোর্ট', 'desc' => 'ওয়েবসাইট লাইভ করুন এবং ২৪/৭ টেকনিক্যাল সাপোর্ট উপভোগ করুন।'],
                    ],
                ],
            ],
            'team_section' => [
                'badge' => 'আমাদের এক্সপার্টরা',
                'title' => 'আমাদের সকল <span class="home-team__accent">টিম মেম্বার</span> দেখুন',
                'description' => 'অভিজ্ঞ ডিজাইনার, ডেভেলপার ও মার্কেটিং বিশেষজ্ঞ — যারা আপনার প্রজেক্টকে সফল করতে প্রতিশ্রুতিবদ্ধ।',
                'extra' => ['more_text' => 'সব টিম মেম্বার দেখুন', 'more_url' => '/team'],
            ],
            'brands_section' => [
                'badge' => null,
                'title' => 'যাদের সাথে আমরা কাজ করছি',
                'description' => null,
                'extra' => [],
            ],
            'cta_section' => [
                'badge' => null,
                'title' => 'আজই আপনার ওয়েবসাইট তৈরি করুন',
                'description' => 'আমাদের সাথে কথা বলতে কল করুন অথবা মেসেজ পাঠান',
                'extra' => [
                    'call_text' => 'কল করুন',
                    'message_text' => 'মেসেজ পাঠান',
                ],
            ],
        ];
    }

    public function encodeHeroData(array $input): string
    {
        return json_encode([
            'stats' => $input['stats'] ?? [],
            'buttons' => $input['buttons'] ?? [],
            'mockup' => $input['mockup'] ?? [],
        ], JSON_UNESCAPED_UNICODE);
    }

    private function decodeJson(?string $json): array
    {
        if (!$json) {
            return [];
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }
}
