<?php

namespace App\Support;

class DemoMode
{
    public static function enabled(): bool
    {
        return filter_var(env('DEMO_MODE', false), FILTER_VALIDATE_BOOLEAN);
    }

    public static function adminEmail(): string
    {
        return (string) env('DEMO_ADMIN_EMAIL', 'admin@demo.com');
    }

    public static function adminPassword(): string
    {
        return (string) env('DEMO_ADMIN_PASSWORD', '1234');
    }
}
