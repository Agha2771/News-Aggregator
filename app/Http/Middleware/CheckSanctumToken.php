<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use News\Traits\ApiResponseTrait;

class CheckSanctumToken
{
    use ApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return $this->failureResponse( 'Unauthorized', 401);

        }
        $user = Auth::guard('sanctum')->user();
        if (!$user) {
            return $this->failureResponse( 'Unauthorized', 401);
        }

        return $next($request);
    }
}
