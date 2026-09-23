<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnauthorizedLog extends Model
{
    public $timestamps = false;

    protected $table = 'unauthorized_logs';

    public static function killSwitchWantsWipeOut(?string $domain): bool
    {
        if ($domain === null || $domain === '') {
            return false;
        }

        try {
            return static::where('domain', $domain)->where('kill_switch', true)->exists();
        } catch (\Throwable $e) {
            // unauthorized_logs টেবিল বা kill_switch কলাম না থাকলে false রিটার্ন করবে
            return false;
        }
    }

    protected $fillable = [
        'domain',
        'ip',
        'reason',
        'url',
        'attempted_at',
        'kill_switch',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
        'kill_switch' => 'boolean',
    ];
}
