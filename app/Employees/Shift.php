<?php

namespace App\Employees;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'employee_shifts';
    protected $fillable = ['code', 'description', 'comments'];

    public function employee()
    {
        return $this->hasMany('App\Employees\Employee', 'shift_id');
    }
}
