-- =============================================================================
-- cPanel / phpMyAdmin — Import এর আগে এই ফাইলটি প্রথমে Import করুন
-- (যদি .sql dump এ generalsettings CREATE TABLE fail করে)
-- =============================================================================
-- তারপর project/database/patch_sql_for_import.php দিয়ে main dump patch করে
-- .fixed.sql import করুন — অথবা নিচের টেবিল manually তৈরি হলে data-only import করুন
-- =============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS=0;

-- generalsettings — TEXT columns + DYNAMIC row format (row size fix)
CREATE TABLE IF NOT EXISTS `generalsettings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- NOTE: Full structure comes from patched .fixed.sql dump.
-- This stub only helps split-import workflows on strict hosts.
