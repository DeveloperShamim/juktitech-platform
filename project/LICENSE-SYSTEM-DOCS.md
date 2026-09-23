# License System — সম্পূর্ণ ডকুমেন্টেশন

> **প্রজেক্ট:** `c:\xampp\htdocs\project`
> **তৈরির তারিখ:** ১৫ মে ২০২৬
> **লেখক:** Creative Host BD

---

## সূচিপত্র

1. [সিস্টেম ওভারভিউ](#১-সিস্টেম-ওভারভিউ)
2. [ডাটাবেস কাঠামো](#২-ডাটাবেস-কাঠামো)
3. [Public API — verify-license](#৩-public-api--verify-license)
4. [Admin প্যানেল ফিচার](#৪-admin-প্যানেল-ফিচার)
5. [User প্যানেল ফিচার](#৫-user-প্যানেল-ফিচার)
6. [Stolen Domain — চুরি প্রতিরোধ সিস্টেম](#৬-stolen-domain--চুরি-প্রতিরোধ-সিস্টেম)
7. [Kill Switch — রিমোট ওয়াইপ](#৭-kill-switch--রিমোট-ওয়াইপ)
8. [Cache Signal সিস্টেম](#৮-cache-signal-সিস্টেম)
9. [HMAC — নতুন ক্লায়েন্ট সিকিউরিটি](#৯-hmac--নতুন-ক্লায়েন্ট-সিকিউরিটি)
10. [সার্ভারে Deploy নির্দেশিকা](#১০-সার্ভারে-deploy-নির্দেশিকা)
11. [Client Site Integration গাইড](#১১-client-site-integration-গাইড)
12. [সমস্যা সমাধান (Troubleshooting)](#১২-সমস্যা-সমাধান-troubleshooting)
13. [ফাইল কাঠামো রেফারেন্স](#১৩-ফাইল-কাঠামো-রেফারেন্স)
14. [সকল Route রেফারেন্স](#১৪-সকল-route-রেফারেন্স)

---

## ১. সিস্টেম ওভারভিউ

এই লাইসেন্স সিস্টেমটি দুটি অংশে বিভক্ত:

```
┌─────────────────────────────────────────────────┐
│              LICENSE SERVER (এই প্রজেক্ট)        │
│                                                   │
│  ┌───────────┐   ┌──────────────┐   ┌─────────┐  │
│  │   Admin   │   │    User      │   │ Public  │  │
│  │  Panel    │   │   Panel      │   │   API   │  │
│  └───────────┘   └──────────────┘   └─────────┘  │
│                                         ↑         │
└─────────────────────────────────────────┼─────────┘
                                          │ POST /api/verify-license
              ┌───────────────────────────┼──────────────────────┐
              │                           │                       │
     ┌────────┴──────┐         ┌──────────┴─────┐      ┌────────┴──────┐
     │  Client Site  │         │  Client Site   │      │  Client Site  │
     │  (পুরনো)     │         │  (নতুন HMAC)   │      │   (যেকোনো)   │
     └───────────────┘         └────────────────┘      └───────────────┘
```

### মূল ফিচারসমূহ:

| ফিচার | বিবরণ |
|-------|--------|
| **License Generation** | Admin বা User পেমেন্ট করে নতুন লাইসেন্স তৈরি করতে পারে |
| **Domain Binding** | প্রতিটি লাইসেন্স একটি নির্দিষ্ট ডোমেইনের সাথে যুক্ত |
| **Auto Block** | Invalid লাইসেন্স ব্যবহার করলে ডোমেইন স্বয়ংক্রিয়ভাবে ব্লক হয় |
| **Cache Signal** | Admin ইচ্ছামতো ক্লায়েন্ট সাইটের cache মুছতে পারে |
| **Kill Switch** | Admin যেকোনো সাইটকে রিমোটলি disable করতে পারে |
| **HMAC Security** | নতুন সাইটে HMAC signature দিয়ে আরো শক্তিশালী নিরাপত্তা |
| **License Renewal** | মেয়াদ শেষে UddoktaPay দিয়ে পেমেন্ট করে নবায়ন |

---

## ২. ডাটাবেস কাঠামো

### ২.১ `licenses` টেবিল

```sql
CREATE TABLE `licenses` (
  `id`              bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`         bigint(20) UNSIGNED NOT NULL,
  `domain_name`     varchar(255) NOT NULL,          -- normalize করা (www/https ছাড়া)
  `license_key`     varchar(255) NOT NULL,          -- format: LIC-XXXX-XXXX-XXXX
  `validity_years`  int(11) DEFAULT NULL,
  `expiry_date`     datetime NOT NULL,
  `status`          int(11) NOT NULL DEFAULT 1,     -- 1=Active, 0=Expired/Inactive
  `created_at`      timestamp NULL,
  `updated_at`      timestamp NULL,
  PRIMARY KEY (`id`)
);
```

**গুরুত্বপূর্ণ নিয়ম:**
- `domain_name` সর্বদা normalize হয়ে সেভ হয় (https://, www. বাদ দিয়ে, lowercase)
- `license_key` format: `LIC-XXXX-XXXX-XXXX` (random uppercase alphanumeric)
- `status = 1` মানে Active, `status = 0` মানে Inactive/Expired

---

### ২.২ `stolen_domains` টেবিল

```sql
CREATE TABLE `stolen_domains` (
  `id`            bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain_name`   varchar(255) NOT NULL,
  `ip_address`    varchar(255) DEFAULT NULL,
  `full_url`      text DEFAULT NULL,
  `status`        int(11) NOT NULL DEFAULT 0,       -- 0=Pending, 1=Resolved
  `cache_signal`  varchar(255) DEFAULT NULL,         -- random token, client এটা পরিবর্তন দেখলে cache মুছবে
  `is_blocked`    tinyint(1) NOT NULL DEFAULT 0,     -- 1=Blocked, 0=Unblocked
  `created_at`    timestamp NULL,
  `updated_at`    timestamp NULL,
  PRIMARY KEY (`id`)
);
```

---

### ২.৩ `unauthorized_logs` টেবিল

```sql
CREATE TABLE `unauthorized_logs` (
  `id`            bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain`        varchar(255) NOT NULL,
  `ip`            varchar(255) DEFAULT NULL,
  `reason`        text NOT NULL,
  `url`           text DEFAULT NULL,
  `attempted_at`  timestamp NOT NULL,
  `kill_switch`   tinyint(1) NOT NULL DEFAULT 0,    -- 1=wipe_out চালু
  PRIMARY KEY (`id`)
);
```

---

### ২.৪ `license_renewal_orders` টেবিল

```sql
CREATE TABLE `license_renewal_orders` (
  `id`          bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     bigint(20) UNSIGNED NOT NULL,
  `license_id`  bigint(20) UNSIGNED NOT NULL,
  `amount`      decimal(10,2) NOT NULL DEFAULT 1000.00,
  `invoice_id`  varchar(255) DEFAULT NULL,           -- UddoktaPay invoice ID
  `status`      varchar(255) NOT NULL DEFAULT 'pending',  -- pending, paid, cancelled
  `paid_at`     timestamp NULL,
  `notes`       text DEFAULT NULL,
  `created_at`  timestamp NULL,
  `updated_at`  timestamp NULL,
  PRIMARY KEY (`id`)
);
```

---

### ২.৫ `users` টেবিলে অতিরিক্ত কলাম

```sql
-- ইউজারকে বিনামূল্যে লাইসেন্স জেনারেট করার অনুমতি দেওয়া
ALTER TABLE `users` ADD `free_license_generation` tinyint(1) NOT NULL DEFAULT 0;
```

---

### ২.৬ `generalsettings` টেবিলে অতিরিক্ত কলাম

```sql
-- লাইসেন্স জেনারেশনের মূল্য
ALTER TABLE `generalsettings` ADD `license_generation_price` decimal(10,2) DEFAULT 1000.00;
```

---

## ৩. Public API — verify-license

### Endpoint

```
POST /api/verify-license
Rate Limit: 60 requests/minute (DDoS protection)
```

### Request Parameters

| Parameter | Type | Required | বিবরণ |
|-----------|------|----------|--------|
| `domain` | string | হ্যাঁ | ক্লায়েন্ট সাইটের ডোমেইন |
| `license_key` | string | হ্যাঁ | লাইসেন্স কী |
| `signature` | string | না | HMAC signature (নতুন ক্লায়েন্টের জন্য) |
| `url` | string | না | সম্পূর্ণ URL (logging এর জন্য) |

---

### Response — Valid License ✅

```json
{
    "status": "valid",
    "blocked": false,
    "domain_name": "example.com",
    "license_key": "LIC-ABCD-EFGH-IJKL",
    "expiry_date": "2027-05-15T00:00:00.000000Z",
    "secure_token": "a1b2c3d4...",
    "cache_signal": "xK9mP2...",
    "action": "none"
}
```

---

### Response — Invalid License ❌

```json
{
    "status": "invalid",
    "blocked": true,
    "message": "License key or domain is incorrect or has expired.",
    "action": "none"
}
```

---

### Response — Kill Switch চালু থাকলে ⚠️

```json
{
    "status": "valid",
    "blocked": false,
    "domain_name": "example.com",
    "license_key": "LIC-ABCD-EFGH-IJKL",
    "expiry_date": "2027-05-15T00:00:00.000000Z",
    "secure_token": "a1b2c3d4...",
    "cache_signal": "xK9mP2...",
    "action": "wipe_out"
}
```

> ⚠️ **`action: wipe_out`** পেলে ক্লায়েন্ট সাইট নিজেকে disable করবে।

---

### Response Fields ব্যাখ্যা

| Field | বিবরণ |
|-------|--------|
| `status` | `valid` বা `invalid` |
| `blocked` | ডোমেইন blocked কিনা (`true`/`false`) |
| `secure_token` | `md5(domain + TOKEN_SALT)` — অতিরিক্ত যাচাইয়ের জন্য |
| `cache_signal` | পরিবর্তন হলে ক্লায়েন্ট সাইটের cache মুছতে হবে |
| `action` | `none` বা `wipe_out` |

---

## ৪. Admin প্যানেল ফিচার

### ৪.১ লাইসেন্স তৈরি

**URL:** `/admin/licenses`

Admin নতুন লাইসেন্স তৈরি করতে পারে:
1. ইউজার সিলেক্ট করুন
2. ডোমেইন নাম দিন (www/https দিলে স্বয়ংক্রিয়ভাবে normalize হবে)
3. মেয়াদ সিলেক্ট করুন: **১ বছর / ২ বছর / ৩ বছর**
4. Submit করলে `LIC-XXXX-XXXX-XXXX` format এ key তৈরি হবে

```
উদাহরণ: LIC-K2MN-PQ7R-XY9Z
```

---

### ৪.২ লাইসেন্স এডিট ও মুছা

- **Edit:** ডোমেইন পরিবর্তন, ইউজার পরিবর্তন, Status পরিবর্তন (Active/Inactive)
- **Delete:** লাইসেন্স সম্পূর্ণ মুছে ফেলা

---

### ৪.৩ লাইসেন্স রিনিউয়াল অর্ডার

**URL:** `/admin/license-renewal-orders`

- User সাইট থেকে renew request করলে এখানে দেখা যাবে
- Admin **Mark as Paid** করলে লাইসেন্স আরো ১ বছর extend হবে
- **Mark as Unpaid** করে পূর্বের অবস্থায় ফেরানো যাবে

---

### ৪.৪ Stolen Reports (চুরির রিপোর্ট)

**URL:** `/admin/stolen-reports`

Invalid লাইসেন্স দিয়ে প্রবেশের চেষ্টা করা ডোমেইনগুলো এখানে দেখা যায়।

প্রতিটি রিপোর্টে যা থাকে:
- ডোমেইন নাম + IP ঠিকানা
- সম্পূর্ণ URL
- Status: **Pending / Resolved / Blocked**

**Action বাটনসমূহ:**

| বাটন | কাজ |
|------|-----|
| ⚡ Cache Clear | ক্লায়েন্ট সাইটের cache মুছার signal পাঠানো |
| 🔒 Block | ডোমেইন block করা (ক্লায়েন্ট সাইট বন্ধ হবে) |
| 🔓 Unblock | ডোমেইন unblock করা |
| ☠️ Kill Switch | রিমোট wipe-out চালু/বন্ধ করা |
| 🔄 Status | Pending → Resolved toggle |
| 🗑️ Delete | রিপোর্ট মুছা |

---

## ৫. User প্যানেল ফিচার

### ৫.১ আমার লাইসেন্সসমূহ

**URL:** `/my-licenses`

User তার সকল লাইসেন্স দেখতে পারবে:
- লাইসেন্স কী
- ডোমেইন
- মেয়াদ শেষের তারিখ
- Status (Active/Expired)

---

### ৫.২ নতুন লাইসেন্স তৈরি (User)

**URL:** `/license/generate`

**দুইটি পথ আছে:**

#### বিনামূল্যে (Admin অনুমতি দিলে):
- Admin যদি ইউজারের `free_license_generation = true` করে দেয়
- ইউজার সরাসরি লাইসেন্স পাবে, কোনো পেমেন্ট লাগবে না

#### পেমেন্ট করে (UddoktaPay):
1. ডোমেইন নাম দিন
2. মেয়াদ সিলেক্ট করুন (১/২/৩ বছর)
3. UddoktaPay-তে redirect হবে
4. পেমেন্ট সফল হলে স্বয়ংক্রিয়ভাবে লাইসেন্স তৈরি হবে

> মূল্য = `license_generation_price × মেয়াদ (বছর)`
> উদাহরণ: ১০০০ টাকা × ২ বছর = ২০০০ টাকা

---

### ৫.৩ লাইসেন্স রিনিউ

**URL:** `/license/renew/{id}`

মেয়াদ শেষ হওয়া লাইসেন্স নবায়ন করতে:
1. `/my-licenses` থেকে Renew বাটনে ক্লিক করুন
2. UddoktaPay-তে পেমেন্ট করুন
3. পেমেন্ট সফল হলে লাইসেন্স আরো ১ বছর extend হবে

---

### ৫.৪ লাইসেন্স আপডেট / মুছা

- **Update:** ডোমেইন নাম পরিবর্তন করা যাবে
- **Toggle Status:** লাইসেন্স নিজে Active/Inactive করা
- **Delete:** লাইসেন্স মুছা

---

## ৬. Stolen Domain — চুরি প্রতিরোধ সিস্টেম

### কীভাবে কাজ করে:

```
ক্লায়েন্ট সাইট verify-license কল করে
            │
            ▼
    লাইসেন্স valid?
     /         \
   হ্যাঁ         না
    │              │
    ▼              ▼
is_blocked = 0   stolen_domains এ লগ হয়
(unblock)        is_blocked = 1 সেট হয়
                 cache_signal নতুন হয়
                         │
                         ▼
                 Admin প্যানেলে দেখা যায়
                 Admin block/unblock করতে পারে
```

### Domain Normalize নিয়ম:

```
https://www.example.com/path  →  example.com
http://example.com/           →  example.com
www.EXAMPLE.COM               →  example.com
Example.Com                   →  example.com
```

> এই কারণে domain যেভাবেই পাঠান, একই normalize হবে।

---

## ৭. Kill Switch — রিমোট ওয়াইপ

### কী করে:

Kill Switch চালু করলে ক্লায়েন্ট সাইট `verify-license` বা `cache-signal` check করার সময় `action: wipe_out` response পাবে। এর ফলে ক্লায়েন্ট সাইটের কোড নিজেকে disable করে দেবে।

### কীভাবে চালু করবেন:

1. Admin প্যানেল → **Stolen Reports**
2. নির্দিষ্ট ডোমেইনের ☠️ (Kill Switch) বাটনে ক্লিক করুন
3. Confirmation এ OK করুন

### Kill Switch Status:

| অবস্থা | বিবরণ |
|---------|--------|
| বন্ধ (☠️ ধূসর) | `action: none` — স্বাভাবিক কাজ করবে |
| চালু (☠️ লাল) | `action: wipe_out` — ক্লায়েন্ট সাইট disable হবে |

### API Response যখন Kill Switch চালু:

```json
{
    "action": "wipe_out"
}
```

> ⚠️ **সতর্কতা:** Kill Switch চালু করলে ক্লায়েন্ট সাইট সম্পূর্ণ বন্ধ হয়ে যেতে পারে। সাবধানে ব্যবহার করুন।

---

## ৮. Cache Signal সিস্টেম

### উদ্দেশ্য:

ক্লায়েন্ট সাইট লাইসেন্স ভেরিফিকেশন cache করে রাখতে পারে performance এর জন্য। Cache Signal সিস্টেম ব্যবহার করে Admin ক্লায়েন্ট সাইটের cache মুছতে পারে যখন প্রয়োজন।

### কীভাবে কাজ করে:

```
১. Admin "Cache Clear" বাটনে ক্লিক করে
            │
            ▼
২. stolen_domains.cache_signal নতুন random token পায়
            │
            ▼
৩. পরের বার ক্লায়েন্ট verify করলে নতুন cache_signal পায়
            │
            ▼
৪. ক্লায়েন্ট দেখে signal পরিবর্তিত হয়েছে
            │
            ▼
৫. ক্লায়েন্ট তার local cache মুছে নতুন data লোড করে
```

### Admin এ Cache Clear করা:

1. **Stolen Reports** → ⚡ বাটনে ক্লিক
2. সর্বোচ্চ ৩০ মিনিটের মধ্যে ক্লায়েন্ট সাইটের cache মুছে যাবে

> **Note:** Blocked domain এর cache clear করা যাবে না। আগে Unblock করতে হবে।

---

## ৯. HMAC — নতুন ক্লায়েন্ট সিকিউরিটি

### পুরনো সিস্টেম vs নতুন সিস্টেম:

| | পুরনো (Legacy) | নতুন (HMAC) |
|--|----------------|-------------|
| **Signature** | নেই | `hash_hmac('sha256', domain, HMAC_SALT)` |
| **Stolen Domain Log** | হ্যাঁ (invalid হলে) | না (cleaner approach) |
| **Response** | একই | একই |
| **Security** | মাঝারি | উচ্চ |

### নতুন ক্লায়েন্টের জন্য request:

```php
$domain    = 'example.com';
$signature = hash_hmac('sha256', $domain, 'your_secret_salt_key');

$response = Http::post('https://yourserver.com/api/verify-license', [
    'domain'      => $domain,
    'license_key' => 'LIC-ABCD-EFGH-IJKL',
    'signature'   => $signature,
    'url'         => request()->url(),
]);
```

### HMAC Salt পরিবর্তন:

`app/Http/Controllers/Admin/LicenseController.php` ফাইলে:

```php
private const HMAC_SALT  = 'your_secret_salt_key';   // এটা পরিবর্তন করুন
private const TOKEN_SALT = 'YOUR_SECRET_SALT_HERE_12345';  // এটাও পরিবর্তন করুন
```

> ⚠️ **গুরুত্বপূর্ণ:** Salt পরিবর্তন করলে সব নতুন ক্লায়েন্ট সাইটে নতুন salt ব্যবহার করতে হবে।

---

## ১০. সার্ভারে Deploy নির্দেশিকা

### প্রথমবার Deploy করলে:

#### ধাপ ১: Code upload করুন

সার্ভারে project ফোল্ডার আপলোড করুন।

#### ধাপ ২: `.env` ফাইল সেটআপ করুন

```env
APP_URL=https://yourserver.com
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

#### ধাপ ৩: Migration চালান

**Terminal/SSH থেকে:**
```bash
php artisan migrate
```

**phpMyAdmin থেকে (SSH না থাকলে):**

নিচের SQL গুলো একে একে রান করুন:

```sql
-- ১. stolen_domains এ cache_signal যোগ (যদি আগে না থাকে)
ALTER TABLE `stolen_domains`
  ADD COLUMN IF NOT EXISTS `cache_signal` varchar(255) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `is_blocked` tinyint(1) NOT NULL DEFAULT 0;

-- ২. users টেবিলে free_license_generation যোগ
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `free_license_generation` tinyint(1) NOT NULL DEFAULT 0;

-- ৩. generalsettings টেবিলে license_generation_price যোগ
ALTER TABLE `generalsettings`
  ADD COLUMN IF NOT EXISTS `license_generation_price` decimal(10,2) DEFAULT 1000.00;

-- ৪. unauthorized_logs টেবিল তৈরি
CREATE TABLE IF NOT EXISTS `unauthorized_logs` (
  `id`            bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain`        varchar(255) NOT NULL,
  `ip`            varchar(255) DEFAULT NULL,
  `reason`        text NOT NULL,
  `url`           text DEFAULT NULL,
  `attempted_at`  timestamp NOT NULL,
  `kill_switch`   tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ৫. license_renewal_orders টেবিল তৈরি
CREATE TABLE IF NOT EXISTS `license_renewal_orders` (
  `id`          bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`     bigint(20) UNSIGNED NOT NULL,
  `license_id`  bigint(20) UNSIGNED NOT NULL,
  `amount`      decimal(10,2) NOT NULL DEFAULT 1000.00,
  `invoice_id`  varchar(255) DEFAULT NULL,
  `status`      varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at`     timestamp NULL DEFAULT NULL,
  `notes`       text DEFAULT NULL,
  `created_at`  timestamp NULL DEFAULT NULL,
  `updated_at`  timestamp NULL DEFAULT NULL,
  KEY `idx_status_created` (`status`,`created_at`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_license_id` (`license_id`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### ধাপ ৪: Cache clear করুন

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

---

### আপডেট Deploy করলে (নতুন code push করলে):

```bash
php artisan migrate          # নতুন migration চালান
php artisan config:cache     # config cache আপডেট
php artisan route:cache      # route cache আপডেট
```

---

## ১১. Client Site Integration গাইড

ক্লায়েন্ট সাইটে (যে সাইটগুলো লাইসেন্স চেক করে) নিচের কোড ব্যবহার করুন:

### সহজ পদ্ধতি (পুরনো / Legacy):

```php
<?php

function verifyLicense($domain, $licenseKey, $serverUrl) {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $serverUrl . '/api/verify-license',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'domain'      => $domain,
            'license_key' => $licenseKey,
            'url'         => (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        ]),
        CURLOPT_TIMEOUT        => 10,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

// ব্যবহার
$result = verifyLicense(
    'example.com',
    'LIC-ABCD-EFGH-IJKL',
    'https://yourserver.com'
);

if ($result['status'] === 'valid') {
    if ($result['action'] === 'wipe_out') {
        // সাইট disable করুন
        die('Site has been disabled by administrator.');
    }

    // cache_signal চেক করুন
    $savedSignal = file_get_contents('license_cache.txt');
    if ($savedSignal !== $result['cache_signal']) {
        // cache মুছুন এবং নতুন signal সেভ করুন
        clearLocalCache();
        file_put_contents('license_cache.txt', $result['cache_signal']);
    }

    // লাইসেন্স valid — স্বাভাবিক কাজ করুন

} else {
    die('License invalid or domain blocked.');
}
```

---

### উন্নত পদ্ধতি (নতুন / HMAC):

```php
<?php

define('LICENSE_SERVER', 'https://yourserver.com');
define('LICENSE_DOMAIN',  'example.com');
define('LICENSE_KEY',     'LIC-ABCD-EFGH-IJKL');
define('HMAC_SALT',       'your_secret_salt_key'); // server এর HMAC_SALT এর সাথে মিল রাখুন

function verifyLicenseHmac() {
    $domain    = LICENSE_DOMAIN;
    $signature = hash_hmac('sha256', $domain, HMAC_SALT);

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => LICENSE_SERVER . '/api/verify-license',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => http_build_query([
            'domain'      => $domain,
            'license_key' => LICENSE_KEY,
            'signature'   => $signature,
            'url'         => (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
        ]),
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_SSL_VERIFYPEER => false,
    ]);
    $response = curl_exec($ch);
    curl_close($ch);
    return json_decode($response, true);
}

$result = verifyLicenseHmac();

if (!$result || $result['status'] !== 'valid') {
    die('License verification failed.');
}

if ($result['action'] === 'wipe_out') {
    // Admin kill switch চালু করেছে
    // সাইটের সব গুরুত্বপূর্ণ ফাইল মুছুন বা disable করুন
    die('This installation has been remotely deactivated.');
}

// Cache signal চেক
$cacheFile  = __DIR__ . '/.license_signal';
$savedSignal = file_exists($cacheFile) ? trim(file_get_contents($cacheFile)) : '';
if ($result['cache_signal'] && $savedSignal !== $result['cache_signal']) {
    // cache মুছুন
    if (function_exists('opcache_reset')) {
        opcache_reset();
    }
    file_put_contents($cacheFile, $result['cache_signal']);
}
```

---

## ১২. সমস্যা সমাধান (Troubleshooting)

### সমস্যা ১: পুরনো সাইট কাজ করছে না (HTTP 500)

**কারণ:** সার্ভারে `unauthorized_logs` টেবিল নেই।

**সমাধান:** phpMyAdmin এ এই SQL রান করুন:
```sql
CREATE TABLE IF NOT EXISTS `unauthorized_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `domain` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `reason` text NOT NULL,
  `url` text DEFAULT NULL,
  `attempted_at` timestamp NOT NULL,
  `kill_switch` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

---

### সমস্যা ২: সাইট ব্লক হয়ে আছে কিন্তু লাইসেন্স valid

**কারণ:** `stolen_domains` টেবিলে `is_blocked = 1` আছে।

**সমাধান:**
- Admin প্যানেল → Stolen Reports → Unblock বাটনে ক্লিক করুন
- অথবা phpMyAdmin এ:
```sql
UPDATE stolen_domains SET is_blocked = 0 WHERE domain_name = 'example.com';
```

---

### সমস্যা ৩: Domain normalize এর কারণে লাইসেন্স পাচ্ছে না

**কারণ:** ডোমেইন `www.example.com` দিয়ে সেভ করা কিন্তু API তে `example.com` দিয়ে পাঠানো হচ্ছে।

**সমাধান:** License এডিট করে ডোমেইন দুবার save করুন — এটা স্বয়ংক্রিয়ভাবে normalize হবে।

---

### সমস্যা ৪: Kill Switch বন্ধ করা যাচ্ছে না

**সমাধান:** phpMyAdmin এ:
```sql
UPDATE unauthorized_logs SET kill_switch = 0 WHERE domain = 'example.com';
```

---

### সমস্যা ৫: Cache Signal কাজ করছে না

**সমাধান:**
1. নিশ্চিত করুন ডোমেইনটি `stolen_domains` টেবিলে আছে
2. Admin → Stolen Reports → ⚡ Cache Clear বাটন চাপুন
3. ক্লায়েন্ট সাইট সর্বোচ্চ ৩০ মিনিটের মধ্যে cache মুছবে

---

### সমস্যা ৬: `unauthorized_logs` তে `kill_switch` column নেই

```sql
ALTER TABLE `unauthorized_logs` ADD COLUMN `kill_switch` tinyint(1) NOT NULL DEFAULT 0;
```

---

## ১৩. ফাইল কাঠামো রেফারেন্স

```
project/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Admin/
│   │       │   ├── LicenseController.php          ← মূল লাইসেন্স CRUD + verify API
│   │       │   ├── LicenseRenewalOrderController.php ← রিনিউয়াল অর্ডার ম্যানেজমেন্ট
│   │       │   └── DashboardController.php        ← Stolen Reports, Cache Signal, Kill Switch
│   │       └── User/
│   │           ├── LicenseGenerateController.php  ← User কর্তৃক লাইসেন্স কেনা
│   │           └── ClientController.php           ← User লাইসেন্স ম্যানেজমেন্ট + রিনিউ
│   └── Models/
│       ├── License.php           ← licenses টেবিল + normalizeHostInput() + findForApiVerification()
│       ├── StolenDomain.php      ← stolen_domains টেবিল
│       ├── UnauthorizedLog.php   ← unauthorized_logs টেবিল + killSwitchWantsWipeOut()
│       └── LicenseRenewalOrder.php ← license_renewal_orders টেবিল
├── database/
│   └── migrations/
│       ├── 2026_01_31_080000_create_license_renewal_orders_table.php
│       ├── 2026_03_07_055300_add_license_generation_fields.php
│       ├── 2026_03_07_070257_add_cache_signal_to_stolen_domains.php
│       ├── 2026_05_09_110000_add_is_blocked_to_stolen_domains_table.php
│       ├── 2026_05_12_000001_create_unauthorized_logs_table.php
│       └── 2026_05_13_000001_add_kill_switch_to_unauthorized_logs_table.php
└── routes/
    └── web.php                   ← সকল route সংজ্ঞা
```

---

## ১৪. সকল Route রেফারেন্স

### Public (Authentication ছাড়া)

| Method | URL | কাজ |
|--------|-----|-----|
| POST | `/api/verify-license` | লাইসেন্স ভেরিফিকেশন API |
| GET | `/license` | লাইসেন্স পেজ (frontend) |

---

### Admin Routes (auth:admin required)

| Method | URL | কাজ |
|--------|-----|-----|
| GET | `/admin/licenses` | লাইসেন্স লিস্ট |
| POST | `/admin/licenses/store` | নতুন লাইসেন্স তৈরি |
| GET | `/admin/licenses/edit/{id}` | এডিট ডেটা (Ajax) |
| POST | `/admin/licenses/update/{id}` | লাইসেন্স আপডেট |
| GET | `/admin/licenses/delete/{id}` | লাইসেন্স মুছা |
| GET | `/admin/license-renewal-orders` | রিনিউয়াল অর্ডার লিস্ট |
| POST | `/admin/license-renewal-orders/{id}/mark-paid` | পেইড মার্ক করা |
| POST | `/admin/license-renewal-orders/{id}/mark-unpaid` | আনপেইড মার্ক করা |
| GET | `/admin/stolen-reports` | চুরির রিপোর্ট |
| POST | `/admin/stolen-reports/clear-cache/{id}` | Cache Signal আপডেট |
| POST | `/admin/stolen-reports/block/{id}` | Block/Unblock টগল |
| POST | `/admin/stolen-reports/kill-switch/{id}` | Kill Switch টগল |
| GET | `/admin/stolen-reports/status/{id}` | Status পরিবর্তন |
| GET | `/admin/stolen-reports/delete/{id}` | রিপোর্ট মুছা |
| POST | `/admin/stolen-reports/delete-all` | সব মুছা |
| POST | `/admin/stolen-reports/delete-selected` | সিলেক্টেড মুছা |

---

### User Routes (auth required)

| Method | URL | কাজ |
|--------|-----|-----|
| GET | `/my-licenses` | আমার লাইসেন্সসমূহ |
| GET | `/license/generate` | নতুন লাইসেন্স ফর্ম |
| POST | `/license/generate` | লাইসেন্স জেনারেট |
| GET | `/license/renew/{id}` | রিনিউ পেজ |
| POST | `/license/update/{id}` | লাইসেন্স আপডেট |
| POST | `/license/toggle-status/{id}` | Status টগল |
| POST | `/license/delete/{id}` | লাইসেন্স মুছা |
| GET/POST | `/license/renew/success` | পেমেন্ট সফল callback |
| GET/POST | `/license/renew/cancel` | পেমেন্ট বাতিল |
| POST | `/license/renew/webhook` | UddoktaPay Webhook |
| GET | `/license/renew/thankyou` | Thank You পেজ |
| GET/POST | `/user/license/generate/success` | জেনারেট পেমেন্ট সফল |
| GET | `/user/license/generate/cancel` | জেনারেট পেমেন্ট বাতিল |
| POST | `/user/license/generate/webhook` | জেনারেট Webhook |
| GET | `/user/license/generate/thankyou` | জেনারেট Thank You |

---

## পরিবর্তনের ইতিহাস

| তারিখ | পরিবর্তন |
|-------|----------|
| ১৫ মে ২০২৬ | `UnauthorizedLog::killSwitchWantsWipeOut()` এ try-catch যোগ — সার্ভারে টেবিল না থাকলেও 500 error হবে না |
| ১৫ মে ২০২৬ | `verifyLicense` API তে সব StolenDomain অপারেশন try-catch এ মোড়ানো (backward compatibility) |
| ১৫ মে ২০২৬ | `verifyLicense` response এ `cache_signal` যোগ |
| ১৫ মে ২০২৬ | `DashboardController` এ `Schema::hasTable` চেক যোগ |
| ১৩ মে ২০২৬ | `kill_switch` কলাম `unauthorized_logs` এ যোগ |
| ১২ মে ২০২৬ | `unauthorized_logs` টেবিল তৈরি |
| ৯ মে ২০২৬ | `is_blocked` কলাম `stolen_domains` এ যোগ |
| ৭ মার্চ ২০২৬ | `cache_signal` কলাম `stolen_domains` এ যোগ |
| ৭ মার্চ ২০২৬ | `free_license_generation` ও `license_generation_price` যোগ |
| ৩১ জানুয়ারি ২০২৬ | `license_renewal_orders` টেবিল তৈরি |

---

*এই ডকুমেন্টেশন শেষ আপডেট: ১৫ মে ২০২৬*
