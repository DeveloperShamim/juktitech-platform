<?php

namespace App\Services;

use App\Models\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FrontendThemeService
{
    public function resolve(?GeneralSettings $gs = null): object
    {
        $gs = $gs ?: GeneralSettings::find(1);
        $defaults = $this->defaults();
        $stored = $this->loadStoredSettings();

        $merged = array_merge($defaults, $stored);

        if (!empty($gs->theme_color)) {
            $merged['primary'] = $gs->theme_color;
        }
        if (!empty($gs->footer_color)) {
            $merged['footer_end'] = $gs->footer_color;
        }
        if (!empty($gs->copyright_color)) {
            $merged['footer_start'] = $gs->copyright_color;
        }

        return (object) $this->buildTokens($merged);
    }

    public function defaults(): array
    {
        return [
            'primary' => '#f97316',
            'primary_hover' => '#ea580c',
            'primary_light' => '#fb923c',
            'primary_lighter' => '#ffedd5',
            'primary_muted' => '#fdba74',
            'primary_border' => '#fed7aa',
            'primary_border_soft' => 'rgba(249, 115, 22, 0.25)',
            'primary_bg_soft' => '#fff7ed',
            'primary_bg' => '#fffaf5',
            'primary_bg_light' => '#ffffff',
            'primary_bg_lighter' => '#fff5f2',
            'accent' => '#ff5722',
            'accent_hover' => '#e64a19',
            'secondary' => '#0f172a',
            'hero_accent' => '#fbbf24',
            'footer_start' => '#0f172a',
            'footer_mid' => '#0a0f1d',
            'footer_end' => '#060913',
            'footer_link_hover' => '#f97316',
            'footer_icon' => '#f97316',
            'stats_start' => '#0f172a',
            'stats_mid' => '#1e293b',
            'stats_end' => '#f97316',
            'dark_bg' => '#0c0721',
            'text_dark' => '#0f172a',
            'text_muted' => '#64748b',
            'text_heading' => '#0f172a',
        ];
    }

    public function labels(): array
    {
        return [
            'primary' => 'প্রাইমারি রঙ (মূল ব্র্যান্ড)',
            'primary_hover' => 'প্রাইমারি হোভার',
            'primary_light' => 'প্রাইমারি লাইট (গ্রেডিয়েন্ট)',
            'accent' => 'অ্যাকসেন্ট রঙ (গ্রেডিয়েন্ট শেষ)',
            'accent_hover' => 'অ্যাকসেন্ট হোভার',
            'secondary' => 'সেকেন্ডারি রঙ',
            'hero_accent' => 'হিরো অ্যাকসেন্ট (হলুদ টেক্সট)',
            'footer_start' => 'ফুটার গ্রেডিয়েন্ট শুরু',
            'footer_mid' => 'ফুটার গ্রেডিয়েন্ট মাঝ',
            'footer_end' => 'ফুটার গ্রেডিয়েন্ট শেষ',
            'footer_link_hover' => 'ফুটার লিংক হোভার',
            'footer_icon' => 'ফুটার আইকন রঙ',
            'stats_start' => 'স্ট্যাটস সেকশন শুরু',
            'stats_mid' => 'স্ট্যাটস সেকশন মাঝ',
            'stats_end' => 'স্ট্যাটস সেকশন শেষ',
            'dark_bg' => 'ডার্ক ব্যাকগ্রাউন্ড',
            'text_dark' => 'টেক্সট ডার্ক',
            'text_muted' => 'টেক্সট মিউটেড',
            'text_heading' => 'হেডিং টেক্সট',
        ];
    }

    public function adminFields(): array
    {
        return array_keys($this->labels());
    }

    public function encode(array $input): string
    {
        $payload = [];
        foreach ($this->adminFields() as $key) {
            if (!empty($input[$key])) {
                $payload[$key] = $input[$key];
            }
        }

        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    private function buildTokens(array $c): array
    {
        $rgb = $this->hexToRgb($c['primary']);

        return array_merge($c, [
            'primary_rgb' => implode(', ', $rgb),
            'accent_gradient' => "linear-gradient(135deg, {$c['primary']} 0%, #ea580c 50%, #c2410c 100%)",
            'accent_gradient_hover' => "linear-gradient(135deg, #ea580c 0%, #c2410c 100%)",
            'accent_gradient_vertical' => "linear-gradient(135deg, {$c['primary']} 0%, #ea580c 100%)",
            'accent_gradient_vertical_hover' => "linear-gradient(135deg, #ea580c 0%, #c2410c 100%)",
            'accent_gradient_text' => "linear-gradient(90deg, #ff5722 0%, #ea580c 100%)",
            'accent_gradient_dual' => "linear-gradient(90deg, #ff5722 0%, #f97316 100%)",
            'stats_gradient' => "linear-gradient(135deg, #0f172a 0%, #1e293b 60%, #0f172a 100%)",
            'cta_gradient' => "linear-gradient(135deg, #0c0721 0%, #1e1b4b 50%, #0f172a 100%)",
            'footer_gradient' => "linear-gradient(180deg, #0f172a 0%, #0a0f1d 55%, #060913 100%)",
            'primary_soft' => "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.1)",
            'primary_shadow' => "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.28)",
            'primary_shadow_md' => "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.22)",
            'primary_shadow_lg' => "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.34)",
            'primary_shadow_sm' => "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.08)",
            'primary_shadow_xs' => "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.05)",
            'primary_shadow_card' => "rgba({$rgb[0]}, {$rgb[1]}, {$rgb[2]}, 0.1)",
            'navbar_border' => "rgba(255, 255, 255, 0.08)",
        ]);
    }

    public function saveSettings(array $payload): void
    {
        if (!Schema::hasTable('frontend_theme_settings')) {
            return;
        }

        $exists = DB::table('frontend_theme_settings')->where('id', 1)->exists();

        if ($exists) {
            DB::table('frontend_theme_settings')->where('id', 1)->update([
                'settings' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);
        } else {
            DB::table('frontend_theme_settings')->insert([
                'id' => 1,
                'settings' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function resetSettings(): void
    {
        if (!Schema::hasTable('frontend_theme_settings')) {
            return;
        }

        DB::table('frontend_theme_settings')->where('id', 1)->update([
            'settings' => null,
            'updated_at' => now(),
        ]);
    }

    private function loadStoredSettings(): array
    {
        if (!Schema::hasTable('frontend_theme_settings')) {
            return [];
        }

        $row = DB::table('frontend_theme_settings')->where('id', 1)->first();

        return $this->decodeJson($row->settings ?? null);
    }

    private function decodeJson(?string $json): array
    {
        if (!$json) {
            return [];
        }

        $decoded = json_decode($json, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function hexToRgb(string $hex): array
    {
        $hex = ltrim(trim($hex), '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) !== 6 || !ctype_xdigit($hex)) {
            return [124, 58, 237];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
