<?php

namespace App\Http\Middleware\Employees;

use App\Employees\Role;
use Closure;

class RolesMiddleware
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
        $role = Role::find($request->route('id'));

        if ($role != null) {
            return $next($request);
        }

        return redirect()->back()->with([
            'error' => "The requested role was not found!"
        ]);
    }
}
