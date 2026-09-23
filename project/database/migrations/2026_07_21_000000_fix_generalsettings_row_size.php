<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * generalsettings row size (#1118) — VARCHAR গুলো TEXT এ + DYNAMIC row format
     */
    public function up(): void
    {
        if (!Schema::hasTable('generalsettings')) {
            return;
        }

        try {
            DB::statement('ALTER TABLE `generalsettings` ROW_FORMAT=DYNAMIC');
        } catch (\Throwable $e) {
            // ignore if already set or insufficient privilege
        }

        $textColumns = [
            'footer_details',
            'payment_instructions',
            'tags',
            'footer_text',
            'notice_text',
            'tawk_to',
            'disqus',
            'copyright_text',
            'error_text',
            'error_title',
            'smtp_pass',
            'address',
            'header1_728',
            'header2_728',
            'header3_728',
            'header4_728',
            'adsense_code',
            'search_console',
            'homepageads1_970',
            'homepageads2_970',
            'homepageads3_970',
            'homepageads4_970',
            'sidebar_ads',
            'horizontal_adds1',
            'live_tv',
            'lazy_baner',
            'og_baner',
            'uddoktapay_api_key',
            'uddoktapay_api_url',
            'webview_github_token',
            'fraud_pathao_password',
            'fraud_steadfast_password',
            'fraud_redx_password',
            'fraud_paperfly_password',
            'fraud_carrybee_password',
        ];

        foreach ($textColumns as $column) {
            if (!Schema::hasColumn('generalsettings', $column)) {
                continue;
            }
            try {
                DB::statement("ALTER TABLE `generalsettings` MODIFY `{$column}` TEXT NULL");
            } catch (\Throwable $e) {
                // column type change may fail on some hosts; continue
            }
        }
    }

    public function down(): void
    {
        // irreversible safely — row format / TEXT columns kept
    }
};
