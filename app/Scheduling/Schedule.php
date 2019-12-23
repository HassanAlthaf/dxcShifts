<?php

namespace App\Scheduling;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = ['employee_id', 'schedule_status_id', 'day', 'month', 'year'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo('App\Employees\Employee');
    }

    public function scheduleStatus(): BelongsTo
    {
        return $this->belongsTo('App\Scheduling\ScheduleStatus', 'schedule_status_id', 'id');
    }
}
