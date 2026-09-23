-- generalsettings — unused columns drop + row size fix (#1118)
-- cPanel/phpMyAdmin SQL tab এ পুরো ফাইল run করুন (database select করে)

SET FOREIGN_KEY_CHECKS=0;

CREATE TABLE `generalsettings_new` AS
SELECT
  `id`, `logo`, `footer_logo`, `lazy_baner`, `og_baner`, `favicon`, `loader`, `admin_loader`, `title`,
  `sms_api_key`, `bd_courier_api_key`, `uddoktapay_api_key`, `sms_sender_id`, `masking_sender_id`,
  `email`, `phone`, `address`, `theme_color`, `footer_color`, `tawk_to`, `is_talkto`,
  `disqus`, `is_capcha`, `is_disqus`, `copyright_text`, `copyright_color`, `footer_text`, `tags`,
  `error_photo`, `error_title`, `error_text`,
  `driver`, `smtp_host`, `smtp_port`, `email_encryption`, `smtp_user`, `smtp_pass`, `from_email`, `from_name`,
  `time_zone`, `is_smtp`, `is_verification_email`, `version`,
  `notice_text`, `search_console`,
  `captcha_site_key`, `captcha_secret_key`, `facebook_page_link`, `is_facebook`,
  `footer_details`, `license_generation_price`,
  `payment_bkash_number`, `payment_nagad_number`, `payment_rocket_number`, `payment_auto_approve`, `payment_instructions`, `payment_mode`,
  `payment_bkash_account_type`, `payment_nagad_account_type`, `payment_rocket_account_type`,
  `webview_build_driver`, `webview_github_token`, `webview_github_repo`, `webview_github_workflow`, `webview_github_ref`, `webview_github_callback_secret`,
  `webview_app_build_price`, `webview_template_path`,
  `fraud_pathao_user`, `fraud_pathao_password`, `fraud_steadfast_user`, `fraud_steadfast_password`,
  `fraud_redx_phone`, `fraud_redx_password`, `fraud_paperfly_user`, `fraud_paperfly_password`,
  `fraud_carrybee_phone`, `fraud_carrybee_password`
FROM `generalsettings`
WHERE 1=0;

INSERT INTO `generalsettings_new`
SELECT
  `id`, `logo`, `footer_logo`, `lazy_baner`, `og_baner`, `favicon`, `loader`, `admin_loader`, `title`,
  `sms_api_key`, `bd_courier_api_key`, `uddoktapay_api_key`, `sms_sender_id`, `masking_sender_id`,
  `email`, `phone`, `address`, `theme_color`, `footer_color`, `tawk_to`, `is_talkto`,
  `disqus`, `is_capcha`, `is_disqus`, `copyright_text`, `copyright_color`, `footer_text`, `tags`,
  `error_photo`, `error_title`, `error_text`,
  `driver`, `smtp_host`, `smtp_port`, `email_encryption`, `smtp_user`, `smtp_pass`, `from_email`, `from_name`,
  `time_zone`, `is_smtp`, `is_verification_email`, `version`,
  `notice_text`, `search_console`,
  `captcha_site_key`, `captcha_secret_key`, `facebook_page_link`, `is_facebook`,
  `footer_details`, `license_generation_price`,
  `payment_bkash_number`, `payment_nagad_number`, `payment_rocket_number`, `payment_auto_approve`, `payment_instructions`, `payment_mode`,
  `payment_bkash_account_type`, `payment_nagad_account_type`, `payment_rocket_account_type`,
  `webview_build_driver`, `webview_github_token`, `webview_github_repo`, `webview_github_workflow`, `webview_github_ref`, `webview_github_callback_secret`,
  `webview_app_build_price`, `webview_template_path`,
  `fraud_pathao_user`, `fraud_pathao_password`, `fraud_steadfast_user`, `fraud_steadfast_password`,
  `fraud_redx_phone`, `fraud_redx_password`, `fraud_paperfly_user`, `fraud_paperfly_password`,
  `fraud_carrybee_phone`, `fraud_carrybee_password`
FROM `generalsettings`;

DROP TABLE `generalsettings`;
RENAME TABLE `generalsettings_new` TO `generalsettings`;

ALTER TABLE `generalsettings` ADD PRIMARY KEY (`id`);
ALTER TABLE `generalsettings` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `generalsettings` ROW_FORMAT=DYNAMIC;

ALTER TABLE `generalsettings`
  MODIFY `fraud_pathao_password` TEXT NULL,
  MODIFY `fraud_steadfast_password` TEXT NULL,
  MODIFY `fraud_redx_password` TEXT NULL,
  MODIFY `fraud_paperfly_password` TEXT NULL,
  MODIFY `fraud_carrybee_password` TEXT NULL,
  MODIFY `smtp_pass` TEXT NULL,
  MODIFY `sms_api_key` TEXT NULL,
  MODIFY `bd_courier_api_key` TEXT NULL;

SET FOREIGN_KEY_CHECKS=1;

-- uddoktapay_api_url dropped; uddoktapay_api_key kept (CreativePay App API key)
