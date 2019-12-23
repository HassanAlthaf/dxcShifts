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
            'name'         => $row[0],
            'phone_number' => $row[1],
            'role_id'      => Role::whereCode($row[2])->firstOrFail()->id,
            'shift_id'     => Shift::whereCode($row[3])->firstOrFail()->id,
            'order'        => Employee::max('order') + 1 ?? 1
        ]);
    }

    public function rules(): array
    {
        return [
            '0' => 'required|max:255',
            '1' => 'required|digits_between:9,11|unique:employees,phone_number',
            '2' => 'required|exists:employee_roles,code',
            '3' => 'required|exists:employee_shifts,code'
        ];
    }
}
