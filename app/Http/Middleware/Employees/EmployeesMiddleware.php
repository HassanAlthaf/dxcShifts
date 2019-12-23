<?php

namespace App\Http\Middleware\Employees;

use App\Employees\Employee;
use Closure;

class EmployeesMiddleware
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
        $employee = Employee::find($request->route('id'));

        if ($employee != null) {
            return $next($request);
        }

        return redirect()->back()->with([
            'error' => "The requested employee was not found!"
        ]);
    }
}
