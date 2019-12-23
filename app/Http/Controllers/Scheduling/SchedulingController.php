<?php

namespace App\Http\Controllers\Scheduling;

use App\Employees\Employee;
use App\Scheduling\{Schedule, ScheduleExportService, ScheduleStatus};
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class SchedulingController extends Controller
{
    private $messageBag;

    public function __construct(MessageBag $messageBag)
    {
        $this->messageBag = $messageBag;
        $this->middleware('auth');
    }

    public function view(int $year, string $month): View
    {
        if (strlen($month) == 1) {
            $month = '0' . $month;
        }

        return view('home', [
            'year' => $year,
            'month' => $month
        ]);
    }

    private function statusExists(int $id)
    {
        return ScheduleStatus::find($id) != null;
    }

    private function getScheduleForDay($date, $month, $year, $employeeId)
    {
        return Schedule::where('employee_id', $employeeId)->where('day', $date)->where('month', $month)->where('year', $year)->first();
    }

    public function store(Request $request, int $id)
    {
        $employeeId = $id;

        $date   = $request->get('date');
        $month  = $request->get('month');
        $year   = $request->get('year');
        $status = $request->get('scheduleStatus');

        if ($status == '-1') {
            $this->getScheduleForDay($date, $month, $year, $employeeId)->delete();

            return redirect()->back()->with(['success' => 'Schedule has been updated.']);
        }


        if (!$this->statusExists($status)) {
            return redirect()->back()->with(['error' => 'There was a problem in updating the schedule status. Please try again.']);
        }

        $schedule = $this->getScheduleForDay($date, $month, $year, $employeeId);

        if ($schedule == null) {
            Schedule::create([
                'employee_id' => $employeeId,
                'schedule_status_id' => $status,
                'day' => $date,
                'month' => $month,
                'year' => $year
            ]);
        } else {
            $schedule->schedule_status_id = $status;
            $schedule->save();
        }

        $service = new ScheduleExportService($month, $year);

        $service->deliverScheduleByEmail();

        return redirect()->back()->with(['success' => 'Schedule has been updated.']);
    }

    public function exportReport(Request $request, $year, $month)
    {
        $service = new ScheduleExportService($month, $year);

        return $service->render();
    }

    public function viewBulk()
    {
        return view('scheduling.bulk');
    }

    public function submitBulk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status'    => 'required|int|exists:schedule_status,id',
            'days.*'      => 'required|int|min:0|max:6',
            'employees' => 'required',
            'range'     => 'required'
        ]);

        $employees = explode(',', $request->get('employees'));

        $valid = true;

        foreach ($employees as $employee) {
            if (Employee::find($employee) == null) {
                $valid = false;
            }
        }

        if (!$valid) {
            $validator->errors()->add('employees', 'One or more selected employees are invalid.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $range = explode(' - ', $request->get('range'));

        $start = Carbon::createFromFormat('m/d/Y', $range[0]);
        $end = Carbon::createFromFormat('m/d/Y', $range[1]);

        if ($end->lessThanOrEqualTo($start)) {
            $validator->errors()->add('range', 'The start date must be before the end date.');
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validator->validate();

        $dates = [];

        $days = $request->get('days');

        for ($i = 0; $i <= $end->diffInDays($start); $i++) {
            $date = Carbon::createFromFormat('m/d/Y', $range[0])->addDays($i);

            if (in_array($date->dayOfWeek, $days)) {
                $dates[] = $date;
            }
        }

        foreach ($dates as $date) {
            foreach ($employees as $employee) {

                $schedule = $this->getScheduleForDay($date->day, $date->month, $date->year, $employee);

                if ($schedule == null) {
                    Schedule::create([
                        'employee_id' => $employee,
                        'schedule_status_id' => $request->get('status'),
                        'day' => $date->day,
                        'month' => $date->month,
                        'year' => $date->year
                    ]);
                } else {
                    $schedule->schedule_status_id = $request->get('status');
                    $schedule->save();
                }
            }
        }

        $lastDay = $dates[count($dates) - 1];

        $service = new ScheduleExportService($lastDay->month, $lastDay->year);

        $service->deliverScheduleByEmail();

        return redirect()->back()->with(['success' => 'The schedule has been updated.']);
    }

    public function viewClone()
    {
        return view('scheduling.clone');
    }

    public function cloneSchedule(Request $request)
    {
        if (!$request->has('destination_employees')) {
            $this->messageBag->add('destination_employees', 'Select at least ONE destination employee.');

            return redirect()->back()->withErrors($this->messageBag)->withInput();
        }

        $employees = explode(',', $request->get('destination_employees'));

        foreach ($employees as $index => $employee) {
            $employee = Employee::find($employee);

            if ($employee == null) {
                $this->messageBag->add('destination_employees', 'An employee you have selected does not exist in the system.');
                return redirect()->back()->withErrors($this->messageBag)->withInput();
            }
        }

        Validator::make($request->all(), [
            'range'     => 'required',
            'target_employee' => 'required|integer|exists:users,id'
        ])->validate();

        $range = explode(' - ', $request->get('range'));

        $start = Carbon::createFromFormat('m/d/Y', $range[0]);
        $end = Carbon::createFromFormat('m/d/Y', $range[1]);

        if ($end->lessThanOrEqualTo($start)) {
            $this->messageBag->add('range', 'The start date must be before the end date.');
            return redirect()->back()->withErrors($this->messageBag)->withInput();
        }

        for ($i = 0; $i <= $start->diffInDays($end); $i++) {
            $date = Carbon::create($start->year, $start->month, $start->day)->addDays($i);

            $schedule = Schedule::where('day', $date->day)->where('month', $date->month)->where('year', $date->year)->where('employee_id', $request->get('target_employee'))->first();

            if ($schedule == null) {
                continue;
            }

            foreach ($employees as $index => $employee) {
                $current = Schedule::where('day', $date->day)->where('month', $date->month)->where('year', $date->year)->where('employee_id', $employee)->first();

                if ($current != null) {
                    $current->update(['schedule_status_id' => $schedule->schedule_status_id]);

                    continue;
                }

                Schedule::create([
                    'employee_id' => $employee,
                    'schedule_status_id' => $schedule->schedule_status_id,
                    'day' => $schedule->day,
                    'month' => $schedule->month,
                    'year' => $schedule->year
                ]);
            }
        }

        $service = new ScheduleExportService($end->month, $end->year);

        $service->deliverScheduleByEmail();

        return redirect()->back()->with(['success' => 'The schedule has been cloned successfully!']);
    }
}
