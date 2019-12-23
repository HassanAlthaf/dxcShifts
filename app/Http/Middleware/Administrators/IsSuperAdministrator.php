<?php

namespace App\Http\Middleware\Administrators;

use Illuminate\Support\Facades\Auth;
use Closure;

class IsSuperAdministrator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (Auth::user()->isSuperAdmin()) {
            return $next($request);
        }

        return redirect()->back()->with(['error' => 'You are not a super administrator, therefore, you may not access this feature.']);
    }
}
