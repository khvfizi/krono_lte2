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

Route::redirect('/', '/login');
Auth::routes(['register' => false]);

//Temporary offline login (url /login/offline)
Route::view('/login/offline', 'loginoffline',[]);
Route::post('/login/offline', 'TempController@login')->name('login.offline');
//User record controller
Route::get('/ur/popbyid/{id}', 'URController@popById')->name('ur.popbyid');
Route::get('/ur/listAll', 'URController@listAll')->name('ur.listAll');


// Route::get('/', 'MiscController@index')->name('misc.index');
Route::group(['middleware' => ['auth']], function () {

  Route::get('/home', 'MiscController@home')->name('misc.home');
  Route::get('/role', 'RoleController@index')->name('role.index');

  // clock-in related
  Route::get('/punch',      'MiscController@showPunchView')->name('punch.list');
  Route::post('/punch/in',  'MiscController@doClockIn')->name('punch.in');
  Route::post('/punch/out', 'MiscController@doClockOut')->name('punch.out');

  //List staff & search
  Route::get('/staff', 'Admin\StaffController@showStaff')->name('staff.list');
  Route::post('/staff/search', 'Admin\StaffController@searchStaff')->name('staff.search');

  // admins ------------------------------------

  Route::get('/admin/workday', 'Admin\DayTypeController@index')->name('wd.index');
  Route::post('/admin/workday/add', 'Admin\DayTypeController@add')->name('wd.add');
  Route::post('/admin/workday/edit', 'Admin\DayTypeController@edit')->name('wd.edit');
  Route::post('/admin/workday/delete', 'Admin\DayTypeController@delete')->name('wd.delete');

  Route::get('/admin/cda', 'TempController@loadDummyUser')->name('temp.cda');

  //start state admin
  Route::post('/admin/state/store'    ,'Admin\StateController@store'    )->name('state.store');
  Route::get( '/admin/restState'      ,'Admin\StateController@list'     )->name('state.list');
  Route::post('/admin/state/destroy'  ,'Admin\StateController@destroy'  )->name('state.destroy');
  Route::get( '/admin/state/show'     ,'Admin\StateController@show'   )->name('state.show');
  Route::post( '/admin/state/update'  ,'Admin\StateController@update'   )->name('state.update');
  //end state admin

  //User management
  Route::get('/admin/staff', 'Admin\StaffController@showMgmt')->name('staff.list.mgmt');
  Route::post('/admin/staff/edit', 'Admin\StaffController@updateMgmt')->name('staff.edit.mgmt');

  //User authorization
  Route::get('/admin/staff/auth', 'Admin\StaffController@showRole')->name('staff.list.auth');
  Route::post('/admin/staff/auth/edit', 'Admin\StaffController@updateRole')->name('staff.edit.auth');

  //Role management
  Route::get('admin/role', 'Admin\RoleController@show')->name('role.list');
  Route::post('admin/role/create', 'Admin\RoleController@store')->name('role.store');
  Route::post('admin/role/edit', 'Admin\RoleController@update')->name('role.edit');
  Route::post('admin/role/delete', 'Admin\RoleController@destroy')->name('role.delete');

  //Company
  Route::get( '/admin/company','Admin\CompanyController@index')->name('company.index');
  Route::post('/admin/company/add','Admin\CompanyController@store')->name('company.store');
  Route::get( '/admin/Company/list','Admin\CompanyController@list')->name('company.list');
  Route::post('/admin/company/destroy','Admin\CompanyController@destroy')->name('company.destroy');
  Route::post( '/admin/company/update','Admin\CompanyController@update')->name('company.update');

  // /admins ------------------------------------

  //Log activity
  Route::get('/log/listUserLogs', 'MiscController@listUserLogs')->name('log.listUserLogs');
  Route::get('/log/updUserLogs', 'MiscController@logUserAct')->name('log.logUserAct');
});

Route::group(['prefix' => 'admin/shift_pattern', 'as' => 'sp.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
  Route::get('/', 'ShiftPatternController@index')->name('index');
  Route::post('/add', 'ShiftPatternController@addShiftPattern')->name('add');
  Route::get('/detail', 'ShiftPatternController@viewSPDetail')->name('view');
  Route::post('/edit', 'ShiftPatternController@editShiftPattern')->name('edit');
  Route::post('/del', 'ShiftPatternController@delShiftPattern')->name('delete');
  Route::post('/day/push', 'ShiftPatternController@pushDay')->name('day.add');
  Route::post('/day/pop', 'ShiftPatternController@popDay')->name('day.del');
});
