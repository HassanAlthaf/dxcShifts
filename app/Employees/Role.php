<?php

namespace App\Employees;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'employee_roles';
    protected $fillable = ['code', 'description'];

    public function employee()
    {
        return $this->hasMany('App\Employees\Employee', 'role_id');
    }
}
