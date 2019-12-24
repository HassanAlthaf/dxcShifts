<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/settings', 'HomeController@settings')->name('settings');
Route::post('/settings', 'HomeController@updateSettings')->name('updateSettings');

Route::get('/schedules/{year}/{month}', 'Scheduling\SchedulingController@view')->name('viewMonthlySchedule');

Route::get('/status-types/manage', 'Scheduling\ScheduleStatusController@view')->name('manageStatusTypes');
Route::get('/status-types/create', 'Scheduling\ScheduleStatusController@viewCreate')->name('createStatusType');
Route::post('/status-types/create', 'Scheduling\ScheduleStatusController@create')->name('submitCreateStatusType');
Route::get('/status-types/{id}/update', 'Scheduling\ScheduleStatusController@viewUpdate')->middleware(\App\Http\Middleware\Scheduling\ScheduleStatusMiddleware::class)->name('updateStatusType');
Route::post('/status-types/{id}/update', 'Scheduling\ScheduleStatusController@update')->middleware(\App\Http\Middleware\Scheduling\ScheduleStatusMiddleware::class)->name('submitUpdateStatusType');
Route::get('/status-types/{id}/delete', 'Scheduling\ScheduleStatusController@delete')->middleware(\App\Http\Middleware\Scheduling\ScheduleStatusMiddleware::class)->name('deleteStatusType');

Route::get('/shifts/manage', 'Employees\ShiftsController@view')->name('manageShifts');
Route::get('/shifts/create', 'Employees\ShiftsController@viewCreate')->name('createShift');
Route::post('/shifts/create', 'Employees\ShiftsController@create')->name('submitCreateShift');
Route::get('/shifts/{id}/update', 'Employees\ShiftsController@viewUpdate')->middleware(\App\Http\Middleware\Employees\ShiftsMiddleware::class)->name('updateShift');
Route::post('/shifts/{id}/update', 'Employees\ShiftsController@update')->middleware(\App\Http\Middleware\Employees\ShiftsMiddleware::class)->name('submitUpdateShift');
Route::get('/shifts/{id}/delete', 'Employees\ShiftsController@delete')->middleware(\App\Http\Middleware\Employees\ShiftsMiddleware::class)->name('deleteShift');

Route::post('/employees/csv-import', 'Employees\EmployeesController@csvImport')->name('employeeCSV');
Route::get('/employees/create', 'Employees\EmployeesController@viewCreate')->name('createEmployee');
Route::post('/employees/create', 'Employees\EmployeesController@store')->name('storeEmployee');
Route::post('/employees/define_order', 'Employees\EmployeesController@defineViewOrder')->name('updateEmployeeOrder');
Route::post('/employees/{id}/update', 'Employees\EmployeesController@update')->name('updateEmployee')->middleware(\App\Http\Middleware\Employees\EmployeesMiddleware::class);
Route::get('/employees/{id}', 'Employees\EmployeesController@view')->name('viewEmployee')->middleware(\App\Http\Middleware\Employees\EmployeesMiddleware::class);
Route::get('/employees/{id}/delete', 'Employees\EmployeesController@delete')->name('deleteEmployee')->middleware(\App\Http\Middleware\Employees\EmployeesMiddleware::class);


Route::get('/schedule/employees/{id}', 'Scheduling\SchedulingController@store')->middleware(\App\Http\Middleware\Employees\EmployeesMiddleware::class);
Route::get('/schedule/{year}/{month}/export', 'Scheduling\SchedulingController@exportReport');
Route::get('/schedule/bulk-define', 'Scheduling\SchedulingController@viewBulk')->name('viewBulkScheduler');
Route::post('/schedule/bulk-define', 'Scheduling\SchedulingController@submitBulk')->name('submitBulkScheduler');
Route::get('/schedule-status/report', 'Scheduling\ScheduleReportingController@select')->name('schedulingReport');
Route::get('/schedule-status/report/export', 'Scheduling\ScheduleReportingController@export')->name('exportSchedulingStatusReport');
Route::get('/schedule/clone', 'Scheduling\SchedulingController@viewClone')->name('viewClone');
Route::post('/schedule/clone', 'Scheduling\SchedulingController@cloneSchedule')->name('submitClone');

Route::get('/roles/manage', 'Employees\RolesController@view')->name('manageRoles');
Route::get('/roles/create', 'Employees\RolesController@viewCreate')->name('createRole');
Route::post('/roles/create', 'Employees\RolesController@create')->name('submitCreateRole');
Route::get('/roles/{id}/update', 'Employees\RolesController@viewUpdate')->middleware(\App\Http\Middleware\Employees\RolesMiddleware::class)->name('updateRole');
Route::post('/roles/{id}/update', 'Employees\RolesController@update')->middleware(\App\Http\Middleware\Employees\RolesMiddleware::class)->name('submitUpdateRole');
Route::get('/roles/{id}/delete', 'Employees\RolesController@delete')->middleware(\App\Http\Middleware\Employees\RolesMiddleware::class)->name('deleteRole');

Route::get('/administrators/manage', 'Administrators\AdministratorsController@view')->name('manageAdministrators');
Route::get('/administrators/{id}/delete', 'Administrators\AdministratorsController@delete')->name('deleteAdministrator');

Route::get('/holidays/manage', 'Scheduling\HolidaysController@view')->name('manageHolidays');
Route::get('/holidays/create', 'Scheduling\HolidaysController@viewCreate')->name('createHoliday');
Route::post('/holidays/create', 'Scheduling\HolidaysController@create')->name('submitCreateHoliday');
Route::get('/holidays/{id}/update', 'Scheduling\HolidaysController@viewUpdate')->middleware(\App\Http\Middleware\Scheduling\HolidaysMiddleware::class)->name('updateHoliday');
Route::post('/holidays/{id}/update', 'Scheduling\HolidaysController@update')->middleware(\App\Http\Middleware\Scheduling\HolidaysMiddleware::class)->name('submitUpdateHoliday');
Route::get('/holidays/{id}/delete', 'Scheduling\HolidaysController@delete')->middleware(\App\Http\Middleware\Scheduling\HolidaysMiddleware::class)->name('deleteHoliday');
