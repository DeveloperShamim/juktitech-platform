<?php



namespace App\Http\Middleware;



use App\Services\SiteVisitTracker;

use Closure;

use Illuminate\Http\Request;

use Symfony\Component\HttpFoundation\Response;



class TrackSiteVisit

{

    public function __construct(private SiteVisitTracker $tracker) {}



    public function handle(Request $request, Closure $next): Response

    {

        return $next($request);

    }



    /**

     * Track after the HTTP response is sent — page load is not blocked by DB writes.

     */

    public function terminate(Request $request, Response $response): void

    {

        if ($response->getStatusCode() >= 400) {

            return;

        }



        try {

            $this->tracker->track($request);

        } catch (\Throwable) {

            // Never break the site if tracking fails.

        }

    }

}



