<?php

namespace App\Employees;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    protected $fillable = ['employee_id', 'name', 'phone_number', 'role_id', 'shift_id', 'order'];

    public function role(): BelongsTo
    {
        return $this->belongsTo('App\Employees\Role');
    }

    public function shift(): BelongsTo
    {
        return $this->belongsTo('App\Employees\Shift');
    }

    public function schedule(): HasMany
    {
        return $this->hasMany('App\Scheduling\Schedule');
    }
}
