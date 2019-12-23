<?php

namespace App\Scheduling;

use Illuminate\Database\Eloquent\Model;

class ScheduleStatus extends Model
{
    protected $table = "schedule_status";
    protected $fillable = ['code', 'description', 'comments', 'weight', 'background_color', 'text_color'];
}
