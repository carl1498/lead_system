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
Auth::routes(['register' => false]);

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
    Route::get('/ssw_student', 'studentController@ssw');
    Route::get('/titp_student', 'studentController@titp');
    Route::get('/intern_student', 'studentController@intern');
    
    Route::get('/continue_student', 'studentController@continue_student');
    Route::get('/backout_student', 'studentController@backout_student');
    Route::get('/final_student', 'studentController@final_student');
    Route::get('/view_profile_student/{id}', 'studentController@view_profile');
    Route::get('/courseAll', 'studentController@course_all');
    Route::get('/programAll', 'studentController@program_all');
    Route::get('/schoolAll', 'studentController@school_all');
    Route::get('/benefactorAll', 'studentController@benefactor_all');
    Route::get('/universityAll', 'studentController@university_all');
    Route::get('/companyAll', 'studentController@company_all');
    Route::get('/programSSW', 'studentController@program_ssw');
    Route::get('/programTITP', 'studentController@program_titp');

    //Result Monitoring
    Route::get('/approve_student', 'studentController@approve_student');
    Route::get('/deny_student', 'studentController@deny_student');
    Route::get('/cancel_student', 'studentController@cancel_student');

    Route::post('/save_student', 'studentController@save_student');
    Route::post('/save_language_student', 'studentController@save_language_student');
    Route::post('/save_ssw_student', 'studentController@save_ssw_student');
    Route::post('/save_titp_student', 'studentController@save_titp_student');
    Route::post('/save_intern_student', 'studentController@save_intern_student');
    Route::get('/get_student', 'studentController@get_student');
    Route::get('/delete_student', 'studentController@delete_student')->middleware('student_high');

    //PDF
    Route::get('/studentPDF/{id}', 'pdfController@studentPDF');


    //Student Logs
    Route::get('/student_add_history', 'studentLogsController@add_history_page');
    Route::get('/student_add_history_table', 'studentLogsController@add_history_table');

    Route::get('/student_edit_history', 'studentLogsController@edit_history_page');
    Route::get('/student_edit_history_table', 'studentLogsController@edit_history_table');

    Route::get('/student_delete_history', 'studentLogsController@delete_history_page');
    Route::get('/student_delete_history_table', 'studentLogsController@delete_history_table');

    //STUDENT INFO -- START
    
    Route::get('/get_student_info/{id}', 'studentController@get_student_info');

    //Student Emergency
    Route::get('/view_student_emergency/{id}', 'studentController@view_student_emergency');
    Route::post('/save_student_emergency', 'studentController@save_student_emergency');
    Route::get('/get_student_emergency/{id}', 'studentController@get_student_emergency');
    Route::get('/delete_student_emergency', 'studentController@delete_student_emergency');

    //Student Employment
    Route::get('/view_student_employment/{id}', 'studentController@view_student_employment');
    Route::post('/save_student_emp_history', 'studentController@save_student_emp_history');
    Route::get('/get_student_emp_history/{id}', 'studentController@get_student_emp_history');
    Route::get('/delete_student_emp_history', 'studentController@delete_student_emp_history');

    //Student Educational Background
    Route::get('/view_student_education/{id}', 'studentController@view_student_education');
    Route::post('/save_student_education', 'studentController@save_student_education');
    Route::get('/get_student_education/{id}', 'studentController@get_student_education');
    Route::get('/delete_student_education', 'studentController@delete_student_education');

    //SOA
    Route::group(['middleware' => ['admin']], function(){
        Route::get('/payment_others', 'studentController@payment_others');
        Route::get('/get_employee_first', 'studentController@get_employee_first');
        Route::get('/view_student_soa/{id}', 'studentController@view_student_soa');
        Route::get('/delete_student_soa', 'studentController@delete_student_soa');
        Route::get('/get_student_soa/{id}', 'studentController@get_student_soa');
        Route::post('/save_soa', 'studentController@save_soa');
    });

    //STUDENT INFO -- END
});


//STUDENT CLASS
Route::group(['middleware' => ['auth', 'student_class_page']], function(){
    //Student Class
    Route::get('/classes', 'studentClassController@index');
    Route::get('/get_class/{current_class_tab}', 'studentClassController@get_class');
    Route::get('/senseiClass/{completeCheck}', 'studentClassController@sensei_class');
    Route::get('/dateClass', 'studentClassController@date_class');
    Route::get('/studentClass', 'studentClassController@student_class');
    Route::get('/check_student_class/{student}', 'studentClassController@check_student_class');
    Route::get('/class_students', 'studentClassController@class_students');
    Route::get('/no_class_students', 'studentClassController@no_class_students');
    Route::get('/with_class_students', 'studentClassController@with_class_students');
    Route::get('/get_class_settings/{current_class_select}', 'studentClassController@get_class_settings');
    Route::get('/all_class_students', 'studentClassController@all_class_students');
    Route::get('/view_student_class_history/{id}', 'studentClassController@view_student_class_history');
    Route::get('/student_class_name/{id}', 'studentClassController@student_class_name');

    //Student Class Higher
    Route::group(['middleware' => ['student_class_add']], function(){
        Route::post('/add_class', 'studentClassController@add_class');
        Route::get('/assign_student_class', 'studentClassController@assign_student_class');

        Route::group(['middleware' => ['student_class_high']], function(){
            Route::get('/delete_class', 'studentClassController@delete_class');
            Route::get('/get_student_date/{id}', 'studentClassController@get_student_date');
            Route::post('/edit_student_date', 'studentClassController@edit_student_date');
            Route::get('/remove_student_class/{id}', 'studentClassController@remove_student_class');
            Route::get('/end_class', 'studentClassController@end_class');
            Route::post('/edit_class', 'studentClassController@edit_class')->middleware('student_class_high');
        });
    });
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

        //PDF
        Route::get('/employeePDF/{id}', 'pdfController@employeePDF');
        
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
        Route::get('/delete_educational_background/{id}', 'employeeController@delete_educational_background');
        Route::get('/delete_prev_employment_history/{id}', 'employeeController@delete_prev_employment_history');
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
    Route::get('/getPending', 'invoiceController@get_pending');
    Route::get('/getStarting/{book_type}', 'invoiceController@get_starting');
    Route::post('/save_books', 'invoiceController@save_books');
});

Route::group(['middleware' => ['auth', 'book_management']], function(){
    //Book Management
    Route::get('/book_management', 'bookManagementController@index');
    Route::get('/view_student_books', 'bookManagementController@view_student_books');
    Route::get('/view_ssw_student_books', 'bookManagementController@view_ssw_student_books');
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

//FINANCE ROUTES -- START

Route::group(['middleware' => ['auth', 'expense']], function(){
    Route::get('/expense', 'expenseController@index');
    Route::get('/view_expense_type', 'expenseController@view_expense_type');
    Route::post('/save_expense_type', 'expenseController@save_expense_type');
    Route::get('/view_expense_particular', 'expenseController@view_expense_particular');
    Route::post('/save_expense_particular', 'expenseController@save_expense_particular');
    Route::get('/view_expense', 'expenseController@view_expense');
    Route::post('/save_expense', 'expenseController@save_expense');
    Route::get('/expenseTypeAll', 'expenseController@expenseTypeAll');
    Route::get('/expenseParticularAll', 'expenseController@expenseParticularAll');
    Route::get('/get_expense_type/{id}', 'expenseController@get_expense_type');
    Route::get('/get_expense_particular/{id}', 'expenseController@get_expense_particular');
    Route::get('/get_expense/{id}', 'expenseController@get_expense');
    Route::get('/view_cash_disbursement', 'expenseController@view_cash_disbursement');
    Route::get('/view_fiscal_year', 'expenseController@view_fiscal_year');
    Route::post('/excel_expense', 'excelController@excel_expense');
    Route::post('/excel_fiscal_year', 'excelController@excel_fiscal_year');

    Route::group(['middleware' => ['invoice_high']], function(){
        Route::get('/delete_expense_type', 'expenseController@delete_expense_type');
        Route::get('/delete_expense_particular', 'expenseController@delete_expense_particular');
        Route::get('/delete_expense', 'expenseController@delete_expense');
    });
});

//Tuition Fee

Route::group(['middleware' => ['auth', 'admin']], function(){
    Route::get('/tuition', 'tuitionController@index');
    Route::get('/view_tf_program', 'tuitionController@view_tf_program');
    Route::get('/get_tf_projected/{id}', 'tuitionController@get_tf_projected');

    Route::get('/view_tf_student', 'tuitionController@view_tf_student');
    Route::get('/view_tf_payment', 'tuitionController@view_tf_payment');
    Route::get('/view_sec_bond', 'tuitionController@view_sec_bond');
    Route::get('/view_tf_breakdown', 'tuitionController@view_tf_breakdown');
    Route::get('/view_tf_student_modal/{id}', 'tuitionController@view_tf_student_modal');
    Route::get('/view_summary', 'tuitionController@view_summary');
    Route::get('/view_tf_modal/{id}', 'tuitionController@view_tf_modal');
    Route::get('/view_sb_modal/{id}', 'tuitionController@view_sb_modal');

    Route::post('/save_projection', 'tuitionController@save_projection');
    Route::post('/save_tf_payment', 'tuitionController@save_tf_payment');
    Route::post('/save_sb_payment', 'tuitionController@save_sb_payment');
    Route::get('/get_tf_student', 'tuitionController@get_tf_student');
    Route::get('/get_tf_payment/{id}', 'tuitionController@get_tf_payment');
    Route::get('/get_sb_payment/{id}', 'tuitionController@get_sb_payment');
    Route::get('/delete_tf_payment', 'tuitionController@delete_tf_payment');
    Route::get('/delete_sb_payment', 'tuitionController@delete_sb_payment');

    Route::get('/view_soa', 'tuitionController@view_soa');

    Route::post('/excel_tf_breakdown', 'excelController@excel_tf_breakdown');
    Route::post('/excel_tf_sb_summary', 'excelController@excel_tf_sb_summary');
    Route::post('/excel_soa', 'excelController@excel_soa');
});

//Salary Monitoring

Route::group(['middleware' => ['auth', 'admin']], function(){
    Route::get('/salary', 'salaryController@index');
    Route::get('/view_employee_salary', 'salaryController@view_employee_salary');
    Route::get('/view_salary', 'salaryController@view_salary');
    Route::get('/get_emp_salary/{id}', 'salaryController@get_emp_salary');
    Route::post('/save_emp_salary', 'salaryController@save_emp_salary');
    Route::post('/save_salary', 'salaryController@save_salary');
    Route::post('/bulk_save_salary', 'salaryController@bulk_save_salary');
    Route::get('/emp_salary_select', 'salaryController@emp_salary_select');
    Route::get('/salary_position_select', 'salaryController@salary_position_select');
    Route::get('/get_sal_mon/{id}', 'salaryController@get_sal_mon');
    Route::get('/delete_salary', 'salaryController@delete_salary');
    Route::post('/excel_salary', 'excelController@excel_salary');
    Route::post('/excel_salary_summary', 'excelController@excel_salary_summary');
});

//FINANCE ROUTES -- END

//CLIENT ROUTES -- START

Route::group(['middleware' => ['auth', 'client']], function(){
    Route::get('/client', 'clientController@index');
    Route::get('/view_client', 'clientController@view_client');
    Route::get('/view_client_pic/{id}', 'clientController@view_client_pic');
    Route::post('/save_client', 'clientController@save_client');
    Route::post('/save_client_pic', 'clientController@save_client_pic');
    Route::post('/save_client_bank', 'clientController@save_client_bank');
    Route::get('/get_client/{id}', 'clientController@get_client');
    Route::get('/get_client_pic/{id}', 'clientController@get_client_pic');
    Route::get('/get_pic/{id}', 'clientController@get_pic');
    Route::get('/get_bank/{id}', 'clientController@get_bank');
    Route::get('/delete_client', 'clientController@delete_client');
    Route::get('/delete_pic', 'clientController@delete_pic');
});

Route::group(['middleware' => ['auth', 'order']], function(){
    Route::get('/order', 'orderController@index');
    Route::get('/view_order', 'orderController@view_order');
    Route::get('/get_order/{id}', 'orderController@get_order');
    Route::get('/clientAll', 'orderController@clientAll');
    
    Route::group(['middleware' => ['admin']], function(){
        Route::get('/delete_order', 'orderController@delete_order');
        Route::post('/save_order', 'orderController@save_order');
    });
});

//CLIENT ROUTES -- END