# লাইসেন্স ও সিকিউরিটি API — রিপো সারাংশ

**সম্পূর্ণ ডকুমেন্টেশন (ওয়েব, আপডেটেড):** ব্রাউজারে খুলুন —

`/docs/license-security-api`

(সম্পূর্ণ URL: আপনার লাইসেন্স সার্ভারের রুট + উপরের পাথ। Laravel রুট নাম: `docs.license_security_api`)

এই পেজে আছে: লিগ্যাসি ও HMAC `verify-license`, দুই ধরনের `log-unauthorized`, `cache-signal`, `check-update`, `kill_switch` / `action: wipe_out`, স্টলেন লগ (`stolen_domains`), চেকলিস্ট ও স্থায়ী লিঙ্ক।

---

## দ্রুত রেফারেন্স

| এন্ডপয়েন্ট | মেথড | কন্ট্রোলার |
|-------------|------|------------|
| `/api/verify-license` | POST | `LicenseController::verifyLicense` |
| `/api/log-unauthorized` | POST | `LicenseSecurityApiController::logUnauthorized` |
| `/api/cache-signal` | GET | `LicenseSecurityApiController::cacheSignal` |
| `/api/check-update` | POST | `CheckUpdateController::checkUpdate` (license_key + signature বাধ্য) |
| `/api/download-update` | POST | `DownloadUpdateController::download` |

**Script Update গাইড:** `/docs/script-update-system` — `docs/SCRIPT_UPDATE_SYSTEM.md`

**মনে রাখবেন:** `routes/api.php` এ `verify-license` বা `log-unauthorized` ডুপ্লিকেট রেজিস্টার করবেন না।

---

## সম্পর্কিত কোড

- `routes/web.php` — উপরের পাবলিক রুট ও CSRF exempt তালিকা (`app/Http/Middleware/VerifyCsrfToken.php`)
- `app/Http/Controllers/Admin/LicenseController.php` — একীভূত ভেরিফাই
- `app/Http/Controllers/Api/LicenseSecurityApiController.php` — লগ + ক্যাশ সিগনাল
- `app/Models/UnauthorizedLog.php` — `killSwitchWantsWipeOut()`
- `app/Http/Controllers/Api/LicenseVerificationController.php` — অব্যবহৃত (রুট সরানো); লজিক `LicenseController` এ
