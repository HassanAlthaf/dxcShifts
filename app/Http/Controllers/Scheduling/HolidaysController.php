<?php

namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\Controller;
use App\Scheduling\Holiday;
use App\Scheduling\HolidayTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidaysController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(): string
    {
        $holidays = Holiday::orderBy('date', 'ASC')->get();

        foreach ($holidays as $key => $holiday) {
            $holiday->holidayType = HolidayTypes::VALUES[$holiday->type];
        }

        return view('holidays.manage', [
            'holidays' => json_encode($holidays)
        ]);
    }

    public function viewCreate(): string
    {
        return view('holidays.create');
    }

    public function getValidator(array $data)
    {
        return Validator::make($data, [
            'date' => 'required|date',
            'description' => 'required',
            'type' => 'required|min:0|max:2'
        ]);
    }

    public function create(Request $request)
    {
        $this->getValidator($request->all())->validate();

        $holiday = Holiday::create($request->all());

        return redirect()->route('manageHolidays')->with([
            'success' => "The holiday on '{$holiday->date}' has been created successfully!"
        ]);
    }

    public function update(Request $request, int $id)
    {
        $this->getValidator($request->all())->validate();

        $holiday = Holiday::find($id);

        $holiday->update([
            $request->all()
        ]);

        return redirect()->route('manageHolidays')->with([
            'success' => "The holiday on '{$holiday->date}' has been updated successfully!"
        ]);
    }

    public function viewUpdate(Request $request, int $id)
    {
        return view('holidays.update', [
            'holiday' => Holiday::find($id)
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $holiday = Holiday::find($id);

        $holiday->delete();

        return redirect()->route('manageHolidays')->with([
            'success' => "The holiday on '{$holiday->date}' has been deleted successfully!"
        ]);
    }

    public static function getHolidays($month, $year)
    {
        if ($month == 0 || $year == 0) {
            $now = Carbon::now();

            $month = $now->month;
            $year = $now->year;
        }

        if (strlen($month) == 1) {
            $month = '0' . $month;
        }

        $holidayList = [];

        $holidays = Holiday::where('date', 'LIKE', "${year}-${month}-%")->orderBy('date', 'ASC')->get();

        foreach ($holidays as $key => $holiday) {
            $holiday->holidayType = HolidayTypes::VALUES[$holiday->type];

            $day = explode('-', $holiday)[2];

            $holidayList[(int)$day] = $holiday;
        }

        return $holidayList;
    }
}
