<?php

namespace App\Http\Controllers\Employees;

use App\Employees\Employee;
use App\Http\Controllers\Controller;
use App\Imports\EmployeesImport;
use App\Scheduling\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class EmployeesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function view(Request $request, int $id)
    {
        $employee = Employee::find($id);
        $date = Carbon::now();
        $month = $date->month;

        if (strlen($month) == 1) {
            $month = '0' . $month;
        }

        return view('employees.profile', [
            'employee' => $employee,
            'shift' => $employee->shift,
            'role' => $employee->role,
            'month' => $request->get('month') ?? $month,
            'year' => $request->get('year') ?? $date->year,
        ]);
    }

    public function viewCreate()
    {
        return view('employees.create');
    }

    /*
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone_number' => 'required|digits_between:9,11|unique:employees,phone_number',
            'role_id' => 'required|exists:employee_roles,id',
            'shift_id' => 'required|exists:employee_shifts,id'
        ], [
            'role_id.required' => 'You need to assign a role to the employee.',
            'shift_id.required' => 'You need to assign a shift to the employee.',
            'role_id.exists' => 'The selected role is invalid/does not exist.',
            'shift_id.exists' => 'The selected shift is invalid/does not exist.'
        ]);

        $validator->validate();

        $data = $request->all();

        $data['order'] = (Employee::max('order') ?? 0) + 1;

        $employee = Employee::create($data);


        return redirect()->route('home')->with(['success' => "{$employee->name} has been saved into the system."]);
    }*/

    public function store(Request $request)
    {
        $validEmployees = [];

        foreach ($request->employees as $key => $employee) {

            $isValid = $employee['name'] != null
                    || $employee['phone_number'] != null
                    || $employee['role_id'] != null
                    || $employee['shift_id'] != null;

            if ($isValid) {
                $validEmployees[$key] = $employee;
            }
        }

        if (count($validEmployees) == 0) {
            return redirect()->back()->with(['error' => 'You submitted an empty form.']);
        }

        $validator = Validator::make($validEmployees, [
            '*.name' => 'sometimes|required|max:255',
            '*.phone_number' => 'sometimes|required|digits_between:9,11|unique:employees,phone_number',
            '*.role_id' => 'sometimes|required|exists:employee_roles,id',
            '*.shift_id' => 'sometimes|required|exists:employee_shifts,id'
        ], [
            '*.role_id.required' => 'You need to assign a role to the employee.',
            '*.shift_id.required' => 'You need to assign a shift to the employee.',
            '*.role_id.exists' => 'The selected role is invalid/does not exist.',
            '*.shift_id.exists' => 'The selected shift is invalid/does not exist.',
            '*.phone_number.required' => 'You need to enter the phone number.',
            '*.phone_number.digits_between' => 'The phone number must be between 9 and 11 digits.',
            '*.phone_number.unique' => 'The phone number is already assigned to another employee.',
            '*.name.required' => 'The name field is required',
            '*.name.max' => 'The name field cannot have a value that has more than 255 characters.'
        ]);

        $validator->validate();

        foreach ($validEmployees as $employee) {
            $employee['order'] = (Employee::max('order') ?? 0) + 1;

            Employee::create($employee);
        }

        return redirect()->back()->with(['success' => 'The employees have been added to the system successfully!']);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'phone_number' => 'required|digits_between:9,11|unique:employees,phone_number,' . $id . ',id',
            'role_id' => 'required|exists:employee_roles,id',
            'shift_id' => 'required|exists:employee_shifts,id'
        ], [
            'role_id.required' => 'You need to assign a role to the employee.',
            'shift_id.required' => 'You need to assign a shift to the employee.',
            'role_id.exists' => 'The selected role is invalid/does not exist.',
            'shift_id.exists' => 'The selected shift is invalid/does not exist.'
        ]);

        $validator->validate();

        $employee = Employee::find($id);

        $employee->update([
            'name' => $request->get('name'),
            'phone_number' => $request->get('phone_number'),
            'role_id' => $request->get('role_id'),
            'shift_id' => $request->get('shift_id'),
        ]);

        return redirect()->back()->with(['success' => "{$employee->name}'s profile has been updated."]);
    }

    public function defineViewOrder(Request $request)
    {
        $data = $request->get('data');

        foreach ($data as $key => $value) {
            if (!(isset($value['id']) && isset($value['order']))) {
                continue;
            }

            $employee = Employee::find($value['id']);

            if ($employee == null) {
                continue;
            }

            $employee->update([
                'order' => $value['order']
            ]);
        }

        return redirect()->back()->with(['success' => 'Employee listings order has been updated.']);
    }

    public function csvImport(Request $request)
    {
        Validator::make($request->all(), [
            'csv' => 'required'
        ])->validate();

        try {
            Excel::import(new EmployeesImport(), $request->file('csv'));
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }

        return redirect()->back()->with(['success' => 'The data has been imported']);
    }

    public function delete(Request $request, int $id)
    {
        $employee = Employee::find($id);

        DB::table('schedules')->where('employee_id', $id)->delete();

        $employee->delete();

        return redirect()->to('home')->with(['success' => 'The employee has been removed from our records.']);
    }
}
