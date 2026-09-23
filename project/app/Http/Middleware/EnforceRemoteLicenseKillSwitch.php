<?php

namespace App\Http\Middleware;

use App\Services\SystemGuard;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnforceRemoteLicenseKillSwitch
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $guard = app(SystemGuard::class);

            // .env এ LICENSE_KEY না থাকলে server থেকে auto-fetch করো
            $guard->autoSyncLicense();

            // Kill-switch poll (যথারীতি)
            $guard->pollRemoteKillSwitchSilently();
        } catch (\Throwable $e) {
        }

        return $next($request);
    }
}
