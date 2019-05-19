<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{
    const CONST_ERROR = 'error';

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
        $return = null;
        try {
            JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            $return = response()->json([self::CONST_ERROR => 'Token is Expired'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            $return = response()->json([self::CONST_ERROR => 'Token is Invalid'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            $return = response()->json([self::CONST_ERROR => 'Authorization Token not found'], 401);
        } catch (Exception $e) {
            $return = response()->json([self::CONST_ERROR => 'Unauthorized'], 401);
        }

        if ( ! is_null($return)) {
            return $return;
        }

        return $next($request);
    }
}