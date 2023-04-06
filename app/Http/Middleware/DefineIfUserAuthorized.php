<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class DefineIfUserAuthorized
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('SESSION_TOKEN');

        if ($token) {
            $session = DB::table('sessions')->where('token', $token)->first();
            if ($session && Carbon::parse($session->created_at)->diffInMinutes(Carbon::now()->addHours(5)) < 30) {
                $request->attributes->add(['isAuthorized' => true]);
                view()->share('is_authorized', true);
                return $next($request);
            }
        }

        $request->attributes->add(['isAuthorized' => false]);
        view()->share('is_authorized', false);

        return $next($request);
    }
}
