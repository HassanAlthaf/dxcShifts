<?php

namespace App\Http\Middleware\Employees;

use App\Employees\Shift;
use Closure;

class ShiftsMiddleware
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
        $role = Shift::find($request->route('id'));

        if ($role != null) {
            return $next($request);
        }

        return redirect()->back()->with([
            'error' => "The requested shift was not found!"
        ]);
    }
}
