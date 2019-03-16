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
Route::group(['middleware' => ['auth', 'student_list']], function(){
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
    Route::post('/save_language_student', 'studentController@save_language_student');
    Route::get('/get_student', 'studentController@get_student');
    Route::get('/delete_student', 'studentController@delete_student');
});

Route::group(['middleware' => ['auth', 'student_settings']], function(){
    //Student Settings
    Route::get('/student_settings', 'studentSettingsController@index');
    Route::get('/view_student_settings/{current_settings}', 'studentSettingsController@view');
    Route::post('/save_student_settings', 'studentSettingsController@store');
    Route::get('/get_student_settings/{id}/{current_settings}', 'studentSettingsController@get_student_settings');
    Route::get('/delete_student_settings', 'studentSettingsController@delete_student_settings');
});

//STUDENT ROUTES -- END


//EMPLOYEE ROUTES -- START

Route::group(['middleware' => ['auth', 'employee']], function(){
    Route::get('/employees', 'employeeController@index');
    Route::get('/employee_branch/{current_branch}', 'employeeController@branch');
});

Route::post('/save_employee', 'employeeController@save_employee');
Route::get('/get_employee/{id}', 'employeeController@get_employee');
Route::get('/delete_employee', 'employeeController@delete_employee');

//Account
Route::get('/get_account/{id}', 'employeeController@get_account');
Route::post('/confirm_user', 'employeeController@confirm_user');
Route::post('/save_account', 'employeeController@save_account');

//EMPLOYEE ROUTES -- END

//BOOK ROUTES -- START

Route::group(['middleware' => ['auth', 'invoice']], function(){
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
});

Route::group(['middleware' => ['auth', 'book_management']], function(){
    //Book Management
    Route::get('/book_management', 'bookManagementController@index');
    Route::get('/view_student_books', 'bookManagementController@view_student_books');
    Route::get('/view_books/{book_type_select}', 'bookManagementController@view_books');
    Route::get('/view_branch_books/{book_status}', 'bookManagementController@view_branch_books');
    
    //Request Books
    Route::get('/view_request_books/{book_type_select}', 'requestBooksController@view_request_books');
    Route::get('/getRequestPending/{book_type}', 'requestBooksController@get_pending');
    Route::post('/save_book_request', 'requestBooksController@save_book_request');
    
    //Release Books
    Route::get('/view_release_books/{book_type_select}', 'releaseBooksController@view_release_books');
    Route::get('/get_release_branch', 'releaseBooksController@get_branch');
    Route::get('/get_release_books/{branch_id}', 'releaseBooksController@get_books');
    Route::get('/get_release_pending/{book_type}/{branch_id}', 'releaseBooksController@get_pending');
    Route::post('/save_book_release', 'releaseBooksController@save_book_release');
    
    //Assign Books
    Route::get('/view_assign_books/{book_type_select}', 'assignBooksController@view_assign_books');
    Route::get('/get_assign_student', 'assignBooksController@get_student');
    Route::get('/get_available_book_type/{student_id}', 'assignBooksController@get_available_book_type');
    Route::get('/get_available_book/{book_type}', 'assignBooksController@get_available_book');
    Route::post('/save_book_assign', 'assignBooksController@save_book_assign');
    
    //Lost Books
    Route::get('/view_books_lost/{book_type_select}', 'lostBookController@view_books_lost');
    Route::get('/lost_book/{id}', 'lostBookController@lost_book');
    
    //Return Books
    Route::get('/view_books_return/{book_type_select}', 'returnBookController@view_books_return');
    Route::get('/return_book/{id}', 'returnBookController@return_book');
});

//BOOK ROUTES -- END