<?php

namespace App\Http\Controllers\Administrators;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class AdministratorsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', '\App\Http\Middleware\Administrators\IsSuperAdministrator']);
    }

    public function view()
    {
        return view('administrators.manage');
    }

    public function delete(Request $request, int $id)
    {
        User::find($id)->delete();

        return redirect()->back()->with(['success' => 'The account has been terminated.']);
    }
}
