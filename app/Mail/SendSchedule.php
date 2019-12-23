<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendSchedule extends Mailable
{
    use Queueable, SerializesModels;

    private $schedulePdfPath;
    private $month;
    private $year;

    public function __construct(string $pdfPath, string $month, int $year)
    {
        $this->schedulePdfPath = $pdfPath;
        $this->month = $month;
        $this->year = $year;
    }


    public function build()
    {
        return $this->markdown('emails.schedule', [
                        'month' => $this->month,
                        'year'  => $this->year
                    ])
                    ->attach($this->schedulePdfPath, [
                        'mime' => 'application/pdf',
                    ])
                    ->subject(config('app.name') . " - Latest schedule for {$this->month}/{$this->year}.");
    }
}
