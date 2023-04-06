<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthorized
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->attributes->get('isAuthorized'))
            return $next($request);
        else
            return redirect(RouteServiceProvider::HOME);
    }
}
