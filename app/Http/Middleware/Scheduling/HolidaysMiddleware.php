<?php

namespace App\Http\Middleware\Scheduling;

use App\Scheduling\Holiday;
use Closure;

class HolidaysMiddleware
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
        $holiday = Holiday::find($request->route('id'));

        if ($holiday === null) {
            return redirect()->back()->with(['error' => 'The holiday you tried to access was not found!']);
        }

        return $next($request);
    }
}
