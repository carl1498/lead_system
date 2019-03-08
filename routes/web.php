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
Route::get('/student_result', 'studentController@result');
Route::get('/language_student', 'studentController@language');

Route::get('/continue_student', 'studentController@continue_student');
Route::get('/backout_student', 'studentController@backout_student');
Route::get('/final_student', 'studentController@final_student');

//Result Monitoring
Route::get('/approve_student', 'studentController@approve_student');
Route::get('/deny_student', 'studentController@deny_student');
Route::get('/cancel_student', 'studentController@cancel_student');

Route::post('/save_student', 'studentController@save_student');
Route::get('/get_student', 'studentController@get_student');
Route::get('/delete_student', 'studentController@delete_student');

//Student Settings
Route::get('/student_settings', 'studentSettingsController@index');
Route::get('/view_student_settings/{current_settings}', 'studentSettingsController@view');
Route::post('/save_student_settings', 'studentSettingsController@store');
Route::get('/get_student_settings/{id}/{current_settings}', 'studentSettingsController@get_student_settings');
Route::get('/delete_student_settings', 'studentSettingsController@delete_student_settings');

//STUDENT ROUTES -- END


//EMPLOYEE ROUTES -- START

Route::get('/employees', 'employeeController@index');
Route::get('/employee_branch/{current_branch}', 'employeeController@branch');

Route::post('/save_employee', 'employeeController@save_employee');
Route::get('/get_employee/{id}', 'employeeController@get_employee');
Route::get('/delete_employee', 'employeeController@delete_employee');

//EMPLOYEE ROUTES -- END

//BOOK ROUTES -- START

//Invoice
Route::get('/invoice', 'invoiceController@index');
Route::get('/view_invoice/{invoice_select}', 'invoiceController@view');
Route::post('/save_invoice', 'invoiceController@save_invoice');

//Add Book
Route::get('/viewAddBooks', 'invoiceController@view_add_books');
Route::get('/invoiceAll', 'invoiceController@invoice_all');
Route::get('/bookAll/{invoice_id}', 'invoiceController@book_all');
Route::get('/getPending/{invoice_id}/{book_type}', 'invoiceController@get_pending');
Route::get('/getStarting/{book_type}', 'invoiceController@get_starting');
Route::post('/save_books', 'invoiceController@save_books');

//Book Management
Route::get('/book_management', 'bookManagementController@index');

//Request Books
Route::get('/getRequestPending/{book_type}', 'requestBooksController@get_pending');

//BOOK ROUTES -- END