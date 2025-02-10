<?php

namespace App\Http\Middleware;

use App\Models\Merchant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsMerchant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the user is authenticated and is an instance of the Merchant model.
        if ($request->user() && $request->user() instanceof Merchant) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403); // 403 Forbidden
    }
}
