<?php

namespace App\Http\Controllers\Employees;

use App\Employees\Shift;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShiftsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(): string
    {
        return view('employees.shifts.manage', [
            'shifts' => json_encode(Shift::orderBy('code', 'ASC')->get())
        ]);
    }

    public function viewCreate(): string
    {
        return view('employees.shifts.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:employee_shifts,code|max:255',
            'description' => 'required',
            'comments' => ''
        ]);

        $validator->validate();

        $shift = Shift::create($request->all());

        return redirect()->route('manageShifts')->with([
            'success' => "The shift '{$shift->code}' has been created successfully!"
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'comments' => ''
        ]);

        $validator->validate();

        $shift = Shift::find($id);

        $shift->update([
            'description' => $request->get('description'),
            'comments' => $request->get('comments')
        ]);

        return redirect()->route('manageShifts')->with([
            'success' => "The shift '{$shift->code}' has been updated successfully!"
        ]);
    }

    public function viewUpdate(Request $request, int $id)
    {
        return view('employees.shifts.update', [
            'shift' => Shift::find($id)
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $shift = Shift::find($id);

        $shift->delete();

        return redirect()->route('manageShifts')->with([
            'success' => "The shift '{$shift->code}' has been deleted successfully!"
        ]);
    }
}
