<?php

namespace App\Http\Controllers\Scheduling;

use App\Http\Controllers\Controller;
use App\Scheduling\ScheduleFormatterFacade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ScheduleReportingController extends Controller
{
    private $pdf;

    public function __construct()
    {
        $this->middleware('auth');
        $this->pdf = App::make('dompdf.wrapper');
    }

    public function select(Request $request)
    {
        if (!$request->has('start') || !$request->has('end')) {
            return view('scheduling.stats-report.select');
        }


        $start = Carbon::createFromFormat('m/d/Y', $request->get('start'));
        $end   = Carbon::createFromFormat('m/d/Y', $request->get('end'));

        if ($start->gt($end)) {
            return view('scheduling.stats-report.select')->with(['error' => 'The start date must be before the end date.']);
        }

        return view('scheduling.stats-report.report', [
            'start'     => $start->format('m/d/Y'),
            'end'       => $end->format('m/d/Y'),
            'schedule'  => ScheduleFormatterFacade::getScheduleStatusStatistics($start, $end)
        ]);
    }

    public function export(Request $request)
    {
        if (!$request->has('start') || !$request->has('end')) {
            return redirect()->back()->with(['error' => 'Select a start and end date!']);
        }


        $start = Carbon::createFromFormat('m/d/Y', $request->get('start'));
        $end   = Carbon::createFromFormat('m/d/Y', $request->get('end'));

        if ($start->gt($end)) {
            return redirect()->back()->with(['error' => 'The start date must be before the end date.']);
        }

        return $this->pdf->loadView('exports.scheduling-stats', [
            'start'     => $start->format('m/d/Y'),
            'end'       => $end->format('m/d/Y'),
            'schedule'  => ScheduleFormatterFacade::getScheduleStatusStatistics($start, $end),
            'statuses'    => \App\Scheduling\ScheduleStatus::orderBy('id', 'ASC')->get()
        ])->setPaper('a4', 'landscape')->setWarnings(false)->stream();/*->save($filePath);*/
    }
}
