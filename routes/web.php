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
Auth::routes();

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/logout', function(){
    Auth::logout();
    Session::flush();
    return Redirect::to('/');
})->name('logout');


//GENERAL ROUTES -- START

Route::post('/confirm_user', 'employeeController@confirm_user');

//GENERAL ROUTES -- END

//DASHBOARD ROUTES -- START

Route::get('/dashboard', 'dashboardController@index');
Route::get('/monthly_referral', 'dashboardController@monthly_referral');
Route::get('/branch_signups', 'dashboardController@branch_signups');
Route::get('/get_current_year', 'dashboardController@get_current_year');
Route::get('/update_signup_count', 'dashboardController@update_signup_count');

//DASHBOARD ROUTES -- END

//STUDENT ROUTES -- START

//Student List
Route::group(['middleware' => ['auth', 'student_list']], function(){
    Route::get('/students', 'studentController@index');
    Route::get('/student_branch', 'studentController@branch');
    Route::get('/student_status', 'studentController@status');
    Route::get('/student_result', 'studentController@result');
    Route::get('/language_student', 'studentController@language');
    Route::get('/all_student', 'studentController@all');
    Route::get('/ssv_student', 'studentController@ssv');
    Route::get('/trainee_student', 'studentController@trainee');
    
    Route::get('/continue_student', 'studentController@continue_student');
    Route::get('/backout_student', 'studentController@backout_student');
    Route::get('/final_student', 'studentController@final_student');
    Route::get('/view_profile_student/{id}', 'studentController@view_profile');
    Route::get('/courseAll', 'studentController@course_all');
    Route::get('/programAll', 'studentController@program_all');
    Route::get('/schoolAll', 'studentController@school_all');
    Route::get('/benefactorAll', 'studentController@benefactor_all');
    Route::get('/companyAll', 'studentController@company_all');
    Route::get('/programSSV', 'studentController@program_ssv');

    //Result Monitoring
    Route::get('/approve_student', 'studentController@approve_student');
    Route::get('/deny_student', 'studentController@deny_student');
    Route::get('/cancel_student', 'studentController@cancel_student');

    Route::post('/save_student', 'studentController@save_student');
    Route::post('/save_language_student', 'studentController@save_language_student');
    Route::post('/save_ssv_student', 'studentController@save_ssv_student');
    Route::post('/save_trainee_student', 'studentController@save_trainee_student');
    Route::get('/get_student', 'studentController@get_student');
    Route::get('/delete_student', 'studentController@delete_student')->middleware('student_high');

    //Student Class
    Route::get('/classes', 'studentClassController@index');
    Route::post('/add_class', 'studentClassController@add_class');
    Route::get('/get_class', 'studentClassController@get_class');
    Route::get('/senseiClass/{completeCheck}', 'studentClassController@sensei_class');
    Route::get('/dateClass', 'studentClassController@date_class');
    Route::get('/studentClass', 'studentClassController@student_class');
    Route::get('/check_student_class/{student}', 'studentClassController@check_student_class');
    Route::get('/assign_student_class', 'studentClassController@assign_student_class');

    //Student Logs
    Route::get('/student_add_history', 'studentLogsController@add_history_page');
    Route::get('/student_add_history_table', 'studentLogsController@add_history_table');

    Route::get('/student_edit_history', 'studentLogsController@edit_history_page');
    Route::get('/student_edit_history_table', 'studentLogsController@edit_history_table');

    Route::get('/student_delete_history', 'studentLogsController@delete_history_page');
    Route::get('/student_delete_history_table', 'studentLogsController@delete_history_table');
});

Route::group(['middleware' => ['auth', 'student_settings']], function(){
    //Student Settings
    Route::get('/student_settings', 'studentSettingsController@index');
    Route::get('/view_student_settings/{current_settings}', 'studentSettingsController@view');
    Route::post('/save_student_settings', 'studentSettingsController@store');
    Route::get('/get_student_settings', 'studentSettingsController@get_student_settings');
    //Route::get('/delete_student_settings', 'studentSettingsController@delete_student_settings');
});

//STUDENT ROUTES -- END


//EMPLOYEE ROUTES -- START

Route::group(['middleware' => ['auth', 'employee']], function(){
    Route::get('/employees', 'employeeController@index');
    Route::get('/employee_branch', 'employeeController@branch');
    Route::get('/employee_all/{employee_status}', 'employeeController@all');
    
    Route::get('/get_employee/{id}', 'employeeController@get_employee');
    Route::get('/view_profile_employee/{id}', 'employeeController@view_profile');

    Route::group(['middleware' => ['admin']], function(){
        Route::post('/save_employee', 'employeeController@save_employee');
        Route::get('/delete_employee', 'employeeController@delete_employee');
        Route::post('/save_resign_employee', 'employeeController@resign_employee');
        Route::post('/save_rehire_employee', 'employeeController@rehire_employee');
        
        //Account
        Route::get('/get_account/{id}', 'employeeController@get_account');
        Route::post('/save_account', 'employeeController@save_account');

        //LEAD Employment History
        Route::get('/view_employment_history/{id}', 'employeeController@view_employment_history');
        Route::get('/get_employment_history/{id}', 'employeeController@get_employment_history');
        Route::post('/save_employment_history', 'employeeController@save_employment_history');

        //Previous Employment History
        Route::get('/view_prev_employment_history/{id}', 'employeeController@view_prev_employment_history');
        Route::get('/get_prev_employment_history/{id}', 'employeeController@get_prev_employment_history');
        Route::post('/save_prev_employment_history', 'employeeController@save_prev_employment_history');

        //Educational Background
        Route::get('/view_educational_background/{id}', 'employeeController@view_educational_background');
        Route::get('/get_educational_background/{id}', 'employeeController@get_educational_background');
        Route::post('/save_educational_background', 'employeeController@save_educational_background');

        //Employee Family
        Route::get('/view_employee_emergency/{id}', 'employeeController@view_employee_emergency');
        Route::get('/view_employee_spouse/{id}', 'employeeController@view_employee_spouse');
        Route::get('/view_employee_child/{id}', 'employeeController@view_employee_child');

        Route::post('/save_employee_emergency', 'employeeController@save_employee_emergency');
        Route::post('/save_employee_spouse', 'employeeController@save_employee_spouse');
        Route::post('/save_employee_child', 'employeeController@save_employee_child');

        Route::get('/get_employee_emergency/{id}', 'employeeController@get_employee_emergency');
        Route::get('/get_employee_spouse/{id}', 'employeeController@get_employee_spouse');
        Route::get('/get_employee_child/{id}', 'employeeController@get_employee_child');
        
        Route::get('/delete_employee_emergency/{id}', 'employeeController@delete_employee_emergency');
        Route::get('/delete_employee_spouse/{id}', 'employeeController@delete_employee_spouse');
        Route::get('/delete_employee_child/{id}', 'employeeController@delete_employee_child');
    });

});

//EMPLOYEE ROUTES -- END

//BOOK ROUTES -- START

Route::group(['middleware' => ['auth', 'invoice']], function(){
    //Invoice
    Route::get('/invoice', 'invoiceController@index');
    Route::get('/view_invoice/{type_select}', 'invoiceController@view');
    Route::post('/save_invoice', 'invoiceController@save_invoice');

    
    Route::group(['middleware' => ['invoice_high']], function(){
        Route::get('/delete_invoice/{id}', 'invoiceController@delete_invoice');
        Route::get('/delete_add_book/{id}', 'invoiceController@delete_add_book');
    });


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
    Route::get('/view_ssv_student_books', 'bookManagementController@view_ssv_student_books');
    Route::get('/view_books', 'bookManagementController@view_books');
    Route::get('/view_branch_books', 'bookManagementController@view_branch_books');
    
    //Request Books
    Route::get('/view_request_books', 'requestBooksController@view_request_books');
    Route::get('/getRequestPending/{book_type}', 'requestBooksController@get_pending');
    Route::get('/approve_book_request/{id}', 'requestBooksController@approve_book_request');
    Route::get('/delivered_book_request/{id}', 'requestBooksController@delivered_book_request');
    Route::get('/pending_book_request/{id}', 'requestBooksController@pending_book_request');
    Route::get('/cancel_book_request/{id}', 'requestBooksController@cancel_book_request');
    Route::post('/save_book_request', 'requestBooksController@save_book_request');
    
    //Release Books
    Route::get('/view_release_books', 'releaseBooksController@view_release_books');
    Route::get('/get_release_branch', 'releaseBooksController@get_branch');
    Route::get('/get_release_books/{branch_id}', 'releaseBooksController@get_books');
    Route::get('/get_release_pending/{book_type}/{branch_id}', 'releaseBooksController@get_pending');
    Route::post('/save_book_release', 'releaseBooksController@save_book_release');
    Route::get('/received_book_release/{id}', 'releaseBooksController@received_book_release');
    Route::get('/pending_book_release/{id}', 'releaseBooksController@pending_book_release');
    Route::get('/return_book_release/{id}', 'releaseBooksController@return_book_release');
    
    //Assign Books
    Route::get('/view_assign_books', 'assignBooksController@view_assign_books');
    Route::get('/get_assign_student', 'assignBooksController@get_student');
    Route::get('/get_available_book_type/{student_id}', 'assignBooksController@get_available_book_type');
    Route::get('/get_available_book/{book_type}', 'assignBooksController@get_available_book');
    Route::post('/save_book_assign', 'assignBooksController@save_book_assign');
    
    //Lost Books
    Route::get('/view_books_lost', 'lostBookController@view_books_lost');
    Route::get('/lost_book/{id}', 'lostBookController@lost_book');
    
    //Return Books
    Route::get('/view_books_return', 'returnBookController@view_books_return');
    Route::get('/return_book/{id}', 'returnBookController@return_book');
});

//BOOK ROUTES -- END