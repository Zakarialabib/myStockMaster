<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class CheckInternetConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {

            if (!$this->getInternetStatus()) {
                // Connected to Internet
                // Set the database connection to 'sqlite'
                Config::set('database.default', 'nativephp');
            }
        } catch (\Exception $e) {
            // Not connected to the internet
            // Switch to another database connection
            Config::set('database.default', 'mysql');
        }

        return $next($request);
    }

    private function getInternetStatus(): bool
    {

        /*if (connection_status() === CONNECTION_NORMAL) {
            return true;
        } elseif (in_array(connection_status(), [CONNECTION_ABORTED, CONNECTION_TIMEOUT])) {
            return false;
        }*/

        try {
            $response = Http::timeout(1)->get("https://www.google.com");

            return $response->successful();
        } catch (\Illuminate\Http\Client\ConnectionException $e) {

            return false;
        }
    }
}
