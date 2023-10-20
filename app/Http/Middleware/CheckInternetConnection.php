<?php

namespace App\Http\Middleware;

use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

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
            $client = new Client();
            $response = $client->get(config('app.url'));
            $statusCode = $response->getStatusCode();
            if ($statusCode === 200) {
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
}