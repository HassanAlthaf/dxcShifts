<?php

namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\Controller;
use App\Scheduling\ScheduleStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(): string
    {
        return view('schedule-status.manage', [
            'statusTypes' => json_encode(ScheduleStatus::orderBy('code', 'ASC')->get())
        ]);
    }

    public function viewCreate(): string
    {
        return view('schedule-status.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:schedule_status,code|max:255',
            'description' => 'required',
            'weight' => 'required|numeric',
            'comments' => '',
            'background_color' => 'required',
            'text_color' => 'required'
        ]);

        $validator->validate();

        $scheduleStatus = ScheduleStatus::create($request->all());

        return redirect()->route('manageStatusTypes')->with([
            'success' => "The type '{$scheduleStatus->code}' has been created successfully!"
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
            'weight' => 'required|numeric',
            'comments' => '',
            'background_color' => 'required',
            'text_color' => 'required'
        ]);

        $validator->validate();

        $scheduleStatus = ScheduleStatus::find($id);

        $scheduleStatus->update([
            'description' => $request->get('description'),
            'weight' => $request->get('weight'),
            'comments' => $request->get('comments'),
            'background_color' => $request->get('background_color'),
            'text_color' => $request->get('text_color')
        ]);

        return redirect()->route('manageStatusTypes')->with([
            'success' => "The type '{$scheduleStatus->code}' has been updated successfully!"
        ]);
    }

    public function viewUpdate(Request $request, int $id)
    {
        return view('schedule-status.update', [
            'scheduleStatus' => ScheduleStatus::find($id)
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $scheduleStatus = ScheduleStatus::find($id);

        $scheduleStatus->delete();

        return redirect()->route('manageStatusTypes')->with([
            'success' => "The type '{$scheduleStatus->code}' has been deleted successfully!"
        ]);
    }
}
