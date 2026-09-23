<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    protected $fillable = [
        'logo',
        'footer_logo',
        'favicon',
        'loader',
        'admin_loader',
        'lazy_baner',
        'og_baner',
        'title',
        'theme_color',
        'footer_color',
        'footer_details',
        'tawk_to',
        'is_talkto',
        'is_capcha',
        'is_disqus',
        'disqus',
        'email',
        'phone',
        'address',
        'sms_api_key',
        'uddoktapay_api_key',
        'sms_sender_id',
        'masking_sender_id',
        'payment_bkash_number',
        'payment_nagad_number',
        'payment_rocket_number',
        'payment_bkash_account_type',
        'payment_nagad_account_type',
        'payment_rocket_account_type',
        'payment_auto_approve',
        'payment_instructions',
        'payment_mode',
        'facebook_page_link',
        'is_facebook',
        'captcha_site_key',
        'captcha_secret_key',
        'time_zone',
        'copyright_text',
        'copyright_color',
        'footer_text',
        'tags',
        'error_photo',
        'error_title',
        'error_text',
        'driver',
        'smtp_host',
        'smtp_port',
        'email_encryption',
        'smtp_user',
        'smtp_pass',
        'from_email',
        'from_name',
        'is_smtp',
        'search_console',
        'notice_text',
        'is_verification_email',
        'version',
        'license_generation_price',
    ];

    protected $table = 'generalsettings';

    public $timestamps = false;
}
