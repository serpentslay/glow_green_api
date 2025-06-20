<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Http\Middleware\EnsureClientIsResourceOwner;
use Illuminate\Support\Facades\Auth;

class AuthUserOrClient
{

    protected $checkClient;

    public function __construct(EnsureClientIsResourceOwner $checkClient)
    {
        $this->checkClient = $checkClient;
    }

    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        try {
            return ($this->checkClient->handle($request, $next));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }
}
