<?php
namespace App\Scheduling;

use App\Employees\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ScheduleFormatterFacade
{
    private $days = [''];
    public static function getSchedule(string $year, int $month): Collection
    {
        $employees = Employee::orderBy('order', 'ASC')->get();

        $carbon = Carbon::now();

        $month = $month == 0 ? $carbon->month : $month;
        $year  = $year  == 0 ? $carbon->year : $year;

        if (substr($month, 0, 1) == '0') {
            $month = substr($month, 1, 2);
        }

        foreach ($employees as $index => $employee) {
            $employee->role_code = $employee->role->code;
            $employee->shift_code = $employee->shift->code;

            $schedules = $employee->schedule()->where('month', $month)->where('year', $year)->get();

            foreach ($schedules as $index => $schedule) {
                $scheduleStatus = $schedule->scheduleStatus;

                $employee['day-' . $schedule->day] = $scheduleStatus->code;
                $employee['day-' . $schedule->day . '-background'] = $scheduleStatus->background_color;
                $employee['day-' . $schedule->day . '-text'] = $scheduleStatus->text_color;
            }


        }

        return $employees;
    }

    public static function getScheduleByEmployee(int $employeeId, string $month, int $year): string
    {
        $schedule = Employee::find($employeeId)->schedule()->where('month', $month)->where('year', $year)->get();

        $scheduleList = collect();

        foreach ($schedule as $index => $item) {
            $item->background = $item->scheduleStatus->background_color;
            $item->text_color = $item->scheduleStatus->text_color;
            $scheduleList[$item->day] = $item;
        }

        return $scheduleList->toJson();
    }

    public static function getScheduleStatuses(): string
    {
        return ScheduleStatus::all()->toJson();
    }

    public static function getDayConfigForMonth($month, $year)
    {
        $days = [];

        for ($i = 0; $i < cal_days_in_month(CAL_GREGORIAN, $month, $year); $i++) {
            $carbon = Carbon::create($year, $month, $i + 1);
            $days[$i] = $carbon->shortEnglishDayOfWeek;
        }

        return $days;

    }

    public static function getScheduleStatusStatistics($start, $end)
    {
        $employees = Employee::orderBy('order', 'ASC')->get();

        $records = [];

        foreach ($employees as $employee) {

            $schedules = $employee->schedule()
                ->select('schedules.schedule_status_id', DB::raw('count(*) as total'))
                ->join('schedule_status', 'schedules.schedule_status_id', '=', 'schedule_status.id')
                ->where('schedules.day',   '>=', $start->day)
                ->where('schedules.month', '>=', $start->month)
                ->where('schedules.year',  '>=', $start->year)
                ->where('schedules.day',   '<=', $end->day)
                ->where('schedules.month', '<=', $end->month)
                ->where('schedules.year',  '<=', $end->year)
                ->groupBy('schedules.schedule_status_id')
                ->get();


            $record = [
                'employee_name' => $employee->name,
            ];

            foreach ($schedules as $schedule) {
                $record[$schedule->schedule_status_id] = $schedule->total;
            }

            $records[] = $record;
        }

        return $records;
    }
}