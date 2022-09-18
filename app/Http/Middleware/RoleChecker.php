<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleChecker
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, $studentRole,  $educatorRole)
{
    $roles = Auth::check() ? Auth::user()->role->pluck('name')->toArray() : [];

    if (in_array($studentRole, $roles)) {
        return $next($request);
    } else if (in_array($educatorRole, $roles)) {
        return $next($request);
    }
    return Redirect::route('home');
}
}
