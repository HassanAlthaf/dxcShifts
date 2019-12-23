<?php

namespace App\Http\Middleware\Scheduling;

use App\Scheduling\ScheduleStatus;
use Closure;

class ScheduleStatusMiddleware
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
        $role = ScheduleStatus::find($request->route('id'));

        if ($role != null) {
            return $next($request);
        }

        return redirect()->back()->with([
            'error' => "The requested schedule status type was not found!"
        ]);
    }
}
