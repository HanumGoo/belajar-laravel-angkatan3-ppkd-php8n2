<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminOrManager
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userRoleId = Auth::user()->role_id;
        if ($userRoleId != 3 && $userRoleId != 1) {
            return redirect('/');
        }
        return $next($request);
    }
}
