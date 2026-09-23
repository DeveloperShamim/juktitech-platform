<?php
 
namespace App\Http\Middleware;
 
use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;
 
class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (Cloudflare, cPanel, LiteSpeed). Required for HTTPS
     * session cookies on live server — fixes 419 Page Expired on login.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';
 
    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO;
}