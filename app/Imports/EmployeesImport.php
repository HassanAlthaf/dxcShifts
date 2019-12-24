<?php

namespace App\Imports;

use App\Employees\Employee;
use App\Employees\Role;
use App\Employees\Shift;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeesImport implements ToModel, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new Employee([
            'employee_id'  => $row[0],
            'name'         => $row[1],
            'phone_number' => $row[2],
            'role_id'      => Role::whereCode($row[3])->firstOrFail()->id,
            'shift_id'     => Shift::whereCode($row[4])->firstOrFail()->id,
            'order'        => Employee::max('order') + 1 ?? 1
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|string|unique:employees,employee_id',
            '1' => 'required|max:255',
            '2' => 'required|digits_between:9,11|unique:employees,phone_number',
            '3' => 'required|exists:employee_roles,code',
            '4' => 'required|exists:employee_shifts,code'
        ];
    }
}
