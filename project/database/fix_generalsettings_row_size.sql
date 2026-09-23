-- =============================================================================
-- MySQL Error #1118 (Row size too large) — Import / generalsettings fix
-- phpMyAdmin বা MySQL console এ চালান
-- =============================================================================

-- ১) Import এর আগে (যদি permission থাকে) — নতুন টেবিল DYNAMIC format এ তৈরি হবে
SET GLOBAL innodb_default_row_format = 'DYNAMIC';

-- ২) Database select করুন (নাম আপনার অনুযায়ী বদলান)
-- USE your_database_name;

-- ৩) টেবিল ইতিমধ্যে থাকলে row format + বড় কলাম TEXT এ নিন
ALTER TABLE `generalsettings` ROW_FORMAT=DYNAMIC;

ALTER TABLE `generalsettings`
    MODIFY `footer_details` TEXT NULL,
    MODIFY `payment_instructions` TEXT NULL,
    MODIFY `tags` TEXT NULL,
    MODIFY `footer_text` TEXT NULL,
    MODIFY `notice_text` TEXT NULL,
    MODIFY `tawk_to` TEXT NULL,
    MODIFY `disqus` TEXT NULL,
    MODIFY `copyright_text` TEXT NULL,
    MODIFY `error_text` TEXT NULL,
    MODIFY `error_title` TEXT NULL,
    MODIFY `smtp_pass` TEXT NULL,
    MODIFY `address` TEXT NULL,
    MODIFY `header1_728` TEXT NULL,
    MODIFY `header2_728` TEXT NULL,
    MODIFY `header3_728` TEXT NULL,
    MODIFY `header4_728` TEXT NULL,
    MODIFY `adsense_code` TEXT NULL,
    MODIFY `search_console` TEXT NULL,
    MODIFY `homepageads1_970` TEXT NULL,
    MODIFY `homepageads2_970` TEXT NULL,
    MODIFY `homepageads3_970` TEXT NULL,
    MODIFY `homepageads4_970` TEXT NULL,
    MODIFY `sidebar_ads` TEXT NULL,
    MODIFY `horizontal_adds1` TEXT NULL,
    MODIFY `live_tv` TEXT NULL,
    MODIFY `lazy_baner` TEXT NULL,
    MODIFY `og_baner` TEXT NULL,
    MODIFY `uddoktapay_api_key` TEXT NULL,
    MODIFY `uddoktapay_api_url` TEXT NULL,
    MODIFY `webview_github_token` TEXT NULL;

-- কোনো কলাম না থাকলে সেই লাইনে error আসবে — সেটা skip করুন (অন্য লাইন চালিয়ে যান)

-- =============================================================================
-- .SQL DUMP IMPORT করার সময় (CREATE TABLE fail হলে):
-- dump ফাইল খুলে `generalsettings` এর CREATE TABLE লাইনের শেষে যোগ করুন:
--   ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 ROW_FORMAT=DYNAMIC;
-- অথবা XAMPP my.ini তে যোগ করুন:
--   [mysqld]
--   innodb_default_row_format=DYNAMIC
--   innodb_file_per_table=1
-- তারপর MySQL restart → আবার import
-- =============================================================================
