<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Unused / legacy columns — row size fix (#1118) */
    private function columnsToDrop(): array
    {
        return [
            'photo_card',
            'photo_card_logo',
            'photo_card_ads',
            'is_notice',
            'is_live_tv',
            'is_national',
            'is_pool_on',
            'is_loader',
            'uddoktapay_api_url',
            'header1_728',
            'header2_728',
            'header3_728',
            'header4_728',
            'homepageads1_970',
            'homepageads2_970',
            'homepageads3_970',
            'homepageads4_970',
            'sidebar_ads',
            'horizontal_adds1',
            'live_tv',
            'adsense_code',
        ];
    }

    public function up(): void
    {
        if (!Schema::hasTable('generalsettings')) {
            return;
        }

        $existing = Schema::getColumnListing('generalsettings');
        $drop     = $this->columnsToDrop();
        $keep     = array_values(array_filter($existing, fn (string $c) => !in_array($c, $drop, true)));

        if (count($keep) === count($existing)) {
            return;
        }

        $list = implode(', ', array_map(fn (string $c) => '`' . $c . '`', $keep));

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        DB::statement("CREATE TABLE `generalsettings_new` AS SELECT {$list} FROM `generalsettings` WHERE 1=0");
        DB::statement("INSERT INTO `generalsettings_new` SELECT {$list} FROM `generalsettings`");
        DB::statement('DROP TABLE `generalsettings`');
        DB::statement('RENAME TABLE `generalsettings_new` TO `generalsettings`');

        DB::statement('ALTER TABLE `generalsettings` ADD PRIMARY KEY (`id`)');
        DB::statement('ALTER TABLE `generalsettings` MODIFY `id` INT UNSIGNED NOT NULL AUTO_INCREMENT');

        foreach ($this->textColumnUpgrades() as $column) {
            if (Schema::hasColumn('generalsettings', $column)) {
                try {
                    DB::statement("ALTER TABLE `generalsettings` MODIFY `{$column}` TEXT NULL");
                } catch (\Throwable $e) {
                    // continue
                }
            }
        }

        try {
            DB::statement('ALTER TABLE `generalsettings` ROW_FORMAT=DYNAMIC');
        } catch (\Throwable $e) {
            // continue
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    private function textColumnUpgrades(): array
    {
        return [
            'fraud_pathao_password',
            'fraud_steadfast_password',
            'fraud_redx_password',
            'fraud_paperfly_password',
            'fraud_carrybee_password',
            'fraud_pathao_user',
            'fraud_steadfast_user',
            'fraud_redx_phone',
            'fraud_paperfly_user',
            'fraud_carrybee_phone',
            'smtp_pass',
            'sms_api_key',
            'bd_courier_api_key',
            'email',
            'captcha_site_key',
            'captcha_secret_key',
            'version',
            'webview_template_path',
        ];
    }

    public function down(): void
    {
        // irreversible — dropped legacy ad / uddoktapay columns
    }
};
