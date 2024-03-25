<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // return $next($request);
        $token = $request->bearerToken();
        $ec_pay_api_token = \App\ApiKey::where('ec_pay_api_token', $token)->first();
        if (!$ec_pay_api_token) {
            return response([
                'message' => 'Unauthenticated'
            ], 403);
        }
        return $next($request);
    }
}
