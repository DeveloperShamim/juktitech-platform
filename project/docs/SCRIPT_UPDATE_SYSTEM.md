# স্ক্রিপ্ট আপডেট সিস্টেম — সম্পূর্ণ গাইড

> **লাইসেন্স সার্ভার:** এই প্রজেক্ট (Master)  
> **আপডেট তারিখ:** ২০ মে ২০২৬

---

## সূচিপত্র

1. [ওভারভিউ](#১-ওভারভিউ)
2. [Admin — আপডেট রিলিজ](#২-admin--আপডেট-রিলিজ)
3. [API — check-update](#৩-api--check-update)
4. [API — download-update](#৪-api--download-update)
5. [ক্লায়েন্ট ইন্টিগ্রেশন](#৫-ক্লায়েন্ট-ইন্টিগ্রেশন)
6. [নিরাপত্তা](#৬-নিরাপত্তা)
7. [সমস্যা সমাধান](#৭-সমস্যা-সমাধান)

---

## ১. ওভারভিউ

```
┌─────────────────────┐         POST /api/check-update          ┌──────────────────────┐
│   Client Site       │  (domain + license_key + signature) ──►│  License Server      │
│   (.env keys)       │◄── update_available / up_to_date ──────│  (এই প্রজেক্ট)       │
└─────────────────────┘         POST /api/download-update       └──────────────────────┘
                                        │
                                        ▼
                                 ZIP ফাইল (storage)
```

**গুরুত্বপূর্ণ:** নতুন লাইসেন্স সিস্টেমে ক্লায়েন্টের `.env` এ থাকতে হবে:

| ভেরিয়েবল | উৎস |
|-----------|------|
| `LICENSE_KEY` | Admin push / license-receiver |
| `LICENSE_SIGNATURE` | `licenses.signature_key` (HMAC) |

`signature` প্যারামিটারে ক্লায়েন্ট **অবশ্যই** `LICENSE_SIGNATURE` এর মান পাঠাবে। কী বা সিগনেচার মিললে না → আপডেট **403**।

---

## ২. Admin — আপডেট রিলিজ

1. Admin প্যানেল → **Script Update Files** (`/admin/script-updates`)
2. **Script Name** — ক্লায়েন্ট যে নাম দিয়ে চেক করবে, হুবহু একই (যেমন `Ecommerce Pro`)
3. **Version** — semver স্টাইল (`1.0.1`, `2.0.0`)
4. **ZIP File** — আপডেট প্যাকেজ (সর্বোচ্চ ১GB)
5. **Release Notes** — ঐচ্ছিক

নতুন আপলোড হলে আগের একই `script_name` এর রেকর্ড **Archived** হয়; শুধু সর্বশেষটি **Active (Latest)**।

**প্রয়োজনীয় সার্ভার সেটআপ:**

```bash
php artisan storage:link
```

`storage/app/public/script-updates/` ফোল্ডারে লেখার পারমিশন থাকতে হবে।

---

## ৩. API — check-update

```
POST /api/check-update
Rate limit: 60/min
CSRF: exempt
```

### Request

| ফিল্ড | বাধ্যতা | বিবরণ |
|-------|--------|--------|
| `domain` | হ্যাঁ | ক্লায়েন্ট ডোমেইন (normalize হয়) |
| `license_key` | হ্যাঁ | `LICENSE_KEY` |
| `signature` | হ্যাঁ | `LICENSE_SIGNATURE` (অথবা `signature_key` নামেও পাঠানো যায়) |
| `script_name` | হ্যাঁ | Admin এ যে নাম দিয়েছেন |
| `current_version` | না | ডিফল্ট `1.0.0` |

### Response — আপডেট আছে

```json
{
  "status": "update_available",
  "message": "নতুন আপডেট উপলব্ধ।",
  "version": "1.2.0",
  "current_version": "1.0.0",
  "latest_version": "1.2.0",
  "download_url": "https://your-server.com/api/download-update",
  "release_notes": "Bug fixes"
}
```

### Response — আপ টু ডেট

```json
{
  "status": "up_to_date",
  "message": "আপনার স্ক্রিপ্ট সর্বশেষ ভার্সনে আছে।",
  "current_version": "1.2.0",
  "latest_version": "1.2.0"
}
```

### Response — লাইসেন্স/সিগনেচার ব্যর্থ

```json
{
  "status": "invalid",
  "message": "সিগনেচার যাচাই ব্যর্থ। লাইসেন্স কী ও সিগনেচার মিলছে না।"
}
```

HTTP **403** বা **400**।

---

## ৪. API — download-update

পাবলিক `storage/` URL আর ব্যবহার হয় না — ডাউনলোডেও লাইসেন্স যাচাই লাগে।

```
POST /api/download-update
Rate limit: 30/min
```

### Request

| ফিল্ড | বাধ্যতা |
|-------|--------|
| `domain` | হ্যাঁ |
| `license_key` | হ্যাঁ |
| `signature` | হ্যাঁ |
| `script_name` | হ্যাঁ |
| `version` | না (নির্দিষ্ট ভার্সন; খালি = active latest) |

### Response

সফল হলে **ZIP ফাইল** স্ট্রিম (`Content-Type: application/zip`)।  
হেডার: `X-Update-Version: 1.2.0`

ব্যর্থ হলে JSON (check-update এর মতো)।

---

## ৫. ক্লায়েন্ট ইন্টিগ্রেশন

### PHP (সাধারণ)

```php
define('UPDATE_SERVER', 'https://your-license-server.com');
define('SCRIPT_NAME', 'Ecommerce Pro');
define('APP_VERSION', '1.0.0');

function checkScriptUpdate() {
    $domain = strtolower(preg_replace('#^www\.#i', '', $_SERVER['HTTP_HOST'] ?? ''));
    $licenseKey = getenv('LICENSE_KEY') ?: '';
    $signature  = getenv('LICENSE_SIGNATURE') ?: '';

    $ch = curl_init(UPDATE_SERVER . '/api/check-update');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'domain'          => $domain,
            'license_key'     => $licenseKey,
            'signature'       => $signature,
            'script_name'     => SCRIPT_NAME,
            'current_version' => APP_VERSION,
        ]),
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => true,
    ]);
    $body = curl_exec($ch);
    curl_close($ch);
    return json_decode($body, true);
}

function downloadScriptUpdate() {
    $domain = strtolower(preg_replace('#^www\.#i', '', $_SERVER['HTTP_HOST'] ?? ''));
    $ch = curl_init(UPDATE_SERVER . '/api/download-update');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query([
            'domain'      => $domain,
            'license_key' => getenv('LICENSE_KEY'),
            'signature'   => getenv('LICENSE_SIGNATURE'),
            'script_name' => SCRIPT_NAME,
        ]),
        CURLOPT_TIMEOUT => 600,
    ]);
    $zip = curl_exec($ch);
    curl_close($ch);
    return $zip;
}
```

রেডি ফাইল: `public/script-update-client.php` (সার্ভার রুটে কপি করে ব্যবহার করুন)।

### Laravel ক্লায়েন্ট

`.env`:

```env
LICENSE_REMOTE_BASE_URL=https://your-license-server.com
LICENSE_KEY=LIC-XXXX-XXXX-XXXX
LICENSE_SIGNATURE=abc123...
SCRIPT_NAME=Ecommerce Pro
APP_SCRIPT_VERSION=1.0.0
```

আপডেট চেক করার আগে `SystemGuard` দিয়ে লাইসেন্স সিঙ্ক নিশ্চিত করুন।

---

## ৬. নিরাপত্তা

| নিয়ম | বিবরণ |
|-------|--------|
| সিগনেচার বাধ্যতামূলক | `signature` = DB এর `signature_key` |
| পাবলিক ZIP নেই | শুধু authenticated `download-update` |
| Lifetime | `expiry_date` null হলে মেয়াদ শেষ ধরা হয় না |
| Throttle | check: 60/min, download: 30/min |

সিগনেচার জেনারেশন (সার্ভার):

```php
hash_hmac('sha256', $licenseKey . '|' . $domain, HMAC_SALT);
```

`HMAC_SALT` = `LicenseService::HMAC_SALT` / `LicenseController::HMAC_SALT`।

---

## ৭. সমস্যা সমাধান

| সমস্যা | সমাধান |
|--------|--------|
| 403 সিগনেচার ব্যর্থ | `.env` এর `LICENSE_SIGNATURE` Admin থেকে Push করুন |
| script_name মিলছে না | Admin ও ক্লায়েন্টে একই নাম |
| আপডেট দেখায় না | `current_version` < admin version কিনা চেক করুন |
| ZIP 404 | `php artisan storage:link` ও ফাইল পাথ |
| Lifetime 403 (পুরনো বাগ) | সার্ভার আপডেট করুন — `expiry_date` null সাপোর্ট |

---

**ওয়েব ডক:** `/docs/script-update-system`  
**সম্পর্কিত:** `/docs/license-security-api`
