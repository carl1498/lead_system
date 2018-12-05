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
    return redirect('/login');
});

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Auth::routes();

//DASHBOARD ROUTES -- START

Route::get('/dashboard', 'dashboardController@index');

//DASHBOARD ROUTES -- END

//STUDENT ROUTES -- START

//Student List
Route::get('/students', 'studentController@index');
Route::get('/student_branch', 'studentController@branch');
Route::get('/student_status', 'studentController@status');

Route::get('/continue_student', 'studentController@continue_student');
Route::get('/backout_student', 'studentController@backout_student');
Route::get('/final_student', 'studentController@final_student');
Route::post('/save_student', 'studentController@save_student');
Route::get('/get_student', 'studentController@get_student');
Route::get('/delete_student', 'studentController@delete_student');

//Student Settings
Route::get('/student_settings', 'studentSettingsController@index');
Route::get('/view_student_settings', 'studentSettingsController@view');

/* This part reserved for possible future use
//Schools
Route::get('/schoolsView', 'schoolController@view');
Route::post('/save_school', 'schoolController@save_school');
Route::get('/get_school', 'schoolController@get_school');
Route::get('/delete_school', 'schoolController@delete_school');

//Programs
Route::get('/programsView', 'programController@view');
Route::post('/save_program', 'programController@save_program');
Route::get('/get_program', 'programController@get_program');
Route::get('/delete_program', 'programController@delete_program');

//Benefactors
Route::get('/benefactorsView', 'benefactorController@view');
Route::post('/save_benefactor', 'benefactorController@save_benefactor');
Route::get('/get_benefactor', 'benefactorController@get_benefactor');
Route::get('/delete_benefactor', 'benefactorController@delete_benefactor');
*/

//STUDENT ROUTES -- END


//EMPLOYEE ROUTES -- START

Route::get('/employees', 'employeeController@index');
Route::get('/employee_branch', 'employeeController@branch');

Route::post('/save_employee', 'employeeController@save_employee');
Route::get('/get_employee', 'employeeController@get_employee');
Route::get('/delete_employee', 'employeeController@delete_employee');

//EMPLOYEE ROUTES -- END