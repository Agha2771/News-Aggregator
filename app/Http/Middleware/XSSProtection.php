<?php

namespace App\Http\Middleware;

use Closure;

class XSSProtection
{
    public function handle($request, Closure $next)
    {
        foreach ($request->all() as $key => $value) {
            if (is_string($value)) {
                // Use the fully qualified name
                $request[$key] = \Mews\Purifier\Facades\Purifier::clean($value);
            }
        }
        return $next($request);
    }
}
