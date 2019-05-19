<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $headers = [
            'Access-Control-Allow-Origin'      => env('SPA_URL'),
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With, Database, cache'
        ];

        /**
         * Checking for the OPTIONS request explicitly 
         * and returning access control headers alongside a status code of 200. 
         * Sometimes, user-agent (browser) sends an OPTIONS request first as a form of verification request.
         * However, lumen, somehow, does not recognize the OPTIONS request method and rejects it with a 508. 
         * Hence the need to specifically check this request and grant it access.
         */
        if ($request->isMethod('OPTIONS')) {
            return response()->json('{"method":"OPTIONS"}', 200, $headers);
        }
        
        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->header($key, $value);
        }

        return $response;
    }
}
