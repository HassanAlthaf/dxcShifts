<?php

namespace App\Scheduling;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    protected $fillable = ['date', 'description', 'type'];
}
