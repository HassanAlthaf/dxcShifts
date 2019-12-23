<?php

namespace App\Http\Controllers\Employees;

use App\Employees\Role;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function view(): string
    {
        return view('employees.roles.manage', [
            'roles' => json_encode(Role::orderBy('code', 'ASC')->get())
        ]);
    }

    public function viewCreate(): string
    {
        return view('employees.roles.create');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:employee_roles,code|max:255',
            'description' => 'required'
        ]);

        $validator->validate();

        $role = Role::create($request->all());

        return redirect()->route('manageRoles')->with([
            'success' => "The role '{$role->code}' has been created successfully!"
        ]);
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        $validator->validate();

        $role = Role::find($id);

        $role->update([
            'description' => $request->get('description'),
        ]);

        return redirect()->route('manageRoles')->with([
            'success' => "The role '{$role->code}' has been updated successfully!"
        ]);
    }

    public function viewUpdate(Request $request, int $id)
    {
        return view('employees.roles.update', [
            'role' => Role::find($id)
        ]);
    }

    public function delete(Request $request, int $id)
    {
        $role = Role::find($id);

        $role->delete();

        return redirect()->route('manageRoles')->with([
            'success' => "The role '{$role->code}' has been deleted successfully!"
        ]);
    }
}
