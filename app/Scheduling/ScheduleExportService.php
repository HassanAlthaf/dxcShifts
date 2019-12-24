<?php

namespace App\Scheduling;

use App\Mail\SendSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Mail;

class ScheduleExportService
{
    private $pdf;
    private $month;
    private $year;

    private $months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

    public function __construct($month, $year)
    {
        $this->pdf = App::make('dompdf.wrapper');
        $this->month = $month;
        $this->year = $year;

        return $this;
    }

    public function render()
    {
        $schedule = ScheduleFormatterFacade::getSchedule($this->year, $this->month);

        $filePath = config('dxc-shifts.schedule_exports') . '/' .  $this->year . '-' . $this->month . '-' . Carbon::now() . '.pdf';

        $this->pdf->loadView('exports.schedule', [
            'schedule' => $schedule,
            'days' => ScheduleFormatterFacade::getDayConfigForMonth($this->month, $this->year),
            'month' => $this->months[$this->month - 1],
            'year' => $this->year
        ])->setPaper('a2', 'landscape')->setWarnings(false)->save($filePath);

        return $filePath;
    }

    public function deliverScheduleByEmail()
    {
        if (env('EMAILS_ON')) {
            $sendSchedule = new SendSchedule($this->render(), $this->month, $this->year);

            Mail::to(config('dxc-shifts.target_email'))->send($sendSchedule);
        }
    }
}
