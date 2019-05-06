$(document).ready(function(){

    //INITIALIZE -- START

    var current_branch = 'Makati';
    var current_status = '';
    var current_result = '';
    var current_ssv = '';
    var departure_year;
    var departure_month = $('#month_select').val();
    var current_tab = 'Branch';

    $.ajax({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/get_current_year',
        method: 'get',
        dataType: 'json',
        success: function(data){
            $('#year_select').val(data).trigger('change');
            departure_year = $('#year_select').val();
        }
    });
    
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    $('.select2').select2();
    
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('#student_modal').on('shown.bs.modal', function(){
        $('#fname').focus();
    });

    $("#student_modal").on("hidden.bs.modal", function(e){
        $('#student_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#language_student_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    });

    $('input, select').attr('autocomplete', 'off');

    $('.box-profile').slimScroll({
        size: '8px',
        height: 'auto',
        alwaysVisible: false
    });

    //INITIALIZE -- END


    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        
        if(current_tab == 'Branch'){refresh_student_branch();}
        else if(current_tab == 'Status'){refresh_student_status();}
        else if(current_tab == 'Result'){refresh_student_result();}
        else if(current_tab == 'Language'){refresh_language_student();}
        else if(current_tab == 'SSV'){refresh_ssv_student();}
        else if(current_tab == 'SSV Backout'){refresh_ssv_backout();}

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    var columns_students = [
        {data: 'name', name: 'name'},
        {data: 'contact', name: 'contact'},
        {data: 'program.name', name: 'program'},
        {data: 'school.name', name: 'school'},
        {data: 'benefactor.name', name: 'benefactor'},
        {data: 'gender', name: 'gender'},
        {data: 'age', name: 'age'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'date_of_signup', name: 'date_of_signup'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]

    var columnDefs_students = [
        { width: 220, targets: 0 }, //name
        { width: 90, targets: 1 }, //contact
        { width: 130, targets: 2 }, //program
        { width: 130, targets: 3 }, //school
        { width: 130, targets: 4 }, //benefactor
        { width: 60, targets: 5 }, //gender
        { width: 45, targets: 6 }, //age
        { width: 200, targets: 7 }, //course
        { width: 200, targets: 8 }, //email
        { width: 120, targets: 9 }, //signup
        { width: 120, targets: 10 }, //referral
        { width: 250, targets: 11 }, //remarks
        { width: 150, targets: 12 }, //action
        {defaultContent: "", targets: "_all"}
    ]

    var columns_students_status = [
        {data: 'name', name: 'name'},
        {data: 'branch.name', name: 'branch'},
        {data: 'contact', name: 'contact'},
        {data: 'program.name', name: 'program'},
        {data: 'school.name', name: 'school'},
        {data: 'benefactor.name', name: 'benefactor'},
        {data: 'gender', name: 'gender'},
        {data: 'age', name: 'age'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'date_of_signup', name: 'date_of_signup'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'status', name: 'status'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]
    
    var columnDefs_students_status = [
        { width: 220, targets: 0 },
        { width: 70, targets: 1 },
        { width: 90, targets: 2 },
        { width: 130, targets: 3 },
        { width: 130, targets: 4 },
        { width: 130, targets: 5 },
        { width: 60, targets: 6 },
        { width: 45, targets: 7 },
        { width: 200, targets: 8 },
        { width: 200, targets: 9 },
        { width: 120, targets: 10 },
        { width: 120, targets: 11 },
        { width: 100, targets: 12 },
        { width: 250, targets: 13 },
        { width: 150, targets: 14 },
        {defaultContent: "", targets: "_all"}
    ]

    var columns_students_result = [
        {data: 'name', name: 'name'},
        {data: 'branch.name', name: 'branch'},
        {data: 'program.name', name: 'program'},
        {data: 'school.name', name: 'school'},
        {data: 'coe_status', name: 'coe_status'},
        {data: 'status', name: 'status'},
        {data: 'referral.fname', name: 'referral'},
        {data: "action", orderable:false,searchable:false}
    ]
    
    var columnDefs_students_result = [
        { width: 220, targets: 0 },
        { width: 70, targets: 1 },
        { width: 90, targets: 2 },
        { width: 130, targets: 3 },
        { width: 100, targets: 4 },
        { width: 100, targets: 5 },
        { width: 120, targets: 6 },
        { width: 150, targets: 7 },
        {defaultContent: "", targets: "_all"}
    ]

    var columns_language_students = [
        {data: 'name', name: 'name'},
        {data: 'branch.name', name: 'branch'},
        {data: 'contact', name: 'contact'},
        {data: 'gender', name: 'gender'},
        {data: 'age', name: 'age'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]

    var columnDef_language_students = [
        { width: 220, targets: 0 }, //name
        { width: 70, targets: 1 }, //branch
        { width: 90, targets: 2 }, //contact
        { width: 60, targets: 3 }, //gender
        { width: 45, targets: 4 }, //age
        { width: 200, targets: 5 }, //course
        { width: 200, targets: 6 }, //email
        { width: 120, targets: 7 }, //referral
        { width: 250, targets: 8 }, //remarks
        { width: 150, targets: 9 }, //action
        {defaultContent: "", targets: "_all"}
    ]

    var columns_ssv_students = [
        {data: 'name', name: 'name'},
        {data: 'contact', name: 'contact'},
        {data: 'gender', name: 'gender'},
        {data: 'age', name: 'age'},
        {data: 'program.name', name: 'program'},
        {data: 'benefactor.name', name: 'benefactor'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]

    var columnDef_ssv_students = [
        { width: 220, targets: 0 }, //name
        { width: 90, targets: 1 }, //contact
        { width: 60, targets: 2 }, //gender
        { width: 45, targets: 3 }, //age
        { width: 130, targets: 4 }, //program
        { width: 130, targets: 5 }, //benefactor
        { width: 200, targets: 6 }, //course
        { width: 200, targets: 7 }, //email
        { width: 120, targets: 8 }, //referral
        { width: 250, targets: 9 }, //remarks
        { width: 150, targets: 10 }, //action
        {defaultContent: "", targets: "_all"}
    ]

    var columns_ssv_backout = columns_ssv_students;
    var columnDef_ssv_backout = columnDef_ssv_students;

    function refresh_student_branch(){
        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        students_branch = $('#students_branch').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            stateLoadParams: function( settings, data ) {
                if (data.order) delete data.order;
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            ajax: {
                url: '/student_branch',
                data: {
                    current_branch: current_branch,
                    departure_year: departure_year,
                    departure_month: departure_month
                }
            },
            columnDefs: columnDefs_students,
            columns: columns_students,
            order: [[3,'asc']]
        });

        $('.tooltip').css('width', '400px');
    }

    function refresh_student_status(){

        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        students_status = $('#students_status').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            stateLoadParams: function( settings, data ) {
                if (data.order) delete data.order;
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/student_status',
                data: {
                    current_status: current_status,
                    departure_year: departure_year,
                    departure_month: departure_month
                }
            },
            columnDefs: columnDefs_students_status,
            columns: columns_students_status,
            order: [[3,'asc']]
        });
    }

    function refresh_student_result(){

        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        students_result = $('#students_result').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            stateLoadParams: function( settings, data ) {
                if (data.order) delete data.order;
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/student_result',
                data: {
                    current_result: current_result,
                    departure_year: departure_year,
                    departure_month: departure_month
                }
            },
            columnDefs: columnDefs_students_result,
            columns: columns_students_result,
            order: [[3,'asc']]
        });
    }

    function refresh_language_student(){
        
        departure_year = $('#year_select').val();

        language_students = $('#language_students').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            stateLoadParams: function( settings, data ) {
                if (data.order) delete data.order;
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/language_student',
                data: {
                    departure_year: departure_year
                }
            },
            columnDefs: columnDef_language_students,
            columns: columns_language_students,
        });
    }

    function refresh_ssv_student(){
        
        departure_year = $('#year_select').val();

        ssv_students = $('#ssv_students').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            stateLoadParams: function( settings, data ) {
                if (data.order) delete data.order;
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/ssv_student',
                data: {
                    departure_year: departure_year,
                    current_ssv: current_ssv
                }
            },
            columnDefs: columnDef_ssv_students,
            columns: columns_ssv_students,
            order: [[4,'asc']]
        });
    }

    function refresh_ssv_backout(){
        
        departure_year = $('#year_select').val();

        ssv_backout = $('#ssv_backout').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            stateLoadParams: function( settings, data ) {
                if (data.order) delete data.order;
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/ssv_student',
                data: {
                    departure_year: departure_year,
                    current_ssv: current_ssv
                }
            },
            columnDefs: columnDef_ssv_backout,
            columns: columns_ssv_backout,
            order: [[4,'asc']]
        });
    }

    //DATATABLES -- END

    //FUNCTIONS -- START
    
    //Open Add Students Modal
    $('.add_student').on('click', function(){
        $('#student_modal').modal('toggle');
        $('#student_modal').modal('show');
    });

    $('#birthdate').on('change', function(){
        var tempAge = getAge($(this).val());
        $('#age').val(tempAge);
    })

    $('#l_birthdate').on('change', function(){
        var tempAge = getAge($(this).val());
        $('#l_age').val(tempAge);
    })

    $('#s_birthdate').on('change', function(){
        var tempAge = getAge($(this).val());
        $('#s_age').val(tempAge);
    })
    
    $('.switch').on('click', function(){
        if($('#switch_name').text() == 'SSV'){
            $('#switch_name').text('Student');
            $('.branch_pick, .status_pick, .result_pick, .language_pick').hide();
            $('.ssv_pick, .ssv_backout_pick').show();
            $('#student_list_tab #ssv_first').click();
        }
        else if($('#switch_name').text() == 'Student'){
            $('#switch_name').text('SSV');
            $('.branch_pick, .status_pick, .result_pick, .language_pick').show();
            $('.ssv_pick, .ssv_backout_pick').hide();
            $('#student_list_tab #student_first').click();
        }
    });

    function showMonthSelect(){
        $('.month_select').show();
        $('#month_select').next(".select2-container").show();
        $('.select_description').text('Departure:');
    }

    $('.branch_pick').on('click', function(){
        current_tab = 'Branch';
        current_branch = $(this).text();
        
        showMonthSelect();
    });

    $('.status_pick').on('click', function(){
        current_tab = 'Status';
        current_status = $(this).text();

        showMonthSelect();
    });

    $('.result_pick').on('click', function(){
        current_tab = 'Result';
        current_result = $(this).text();

        showMonthSelect();
    });

    $('.language_pick').on('click', function(){
        current_tab = 'Language';
        
        $('.month_select').hide();
        $('#month_select').next(".select2-container").hide();
        $('.select_description').text('Year:');
    });

    $('.ssv_pick').on('click', function(){
        current_tab = 'SSV';
        current_ssv = $(this).text();
        
        $('.month_select').hide();
        $('#month_select').next(".select2-container").hide();
        $('.select_description').text('Year:');
    });

    $('.ssv_backout_pick').on('click', function(){
        current_tab = 'SSV Backout';
        current_ssv = $(this).text();
        
        $('.month_select').hide();
        $('#month_select').next(".select2-container").hide();
        $('.select_description').text('Year:');
    });

    $(document).on('change', '#year_select, #month_select', function(){
        refresh_student_branch();
        refresh_student_status();
        refresh_student_result();
        refresh_language_student();
        refresh_ssv_student();
    });

    $('.save_student').on('click', function(e){
        e.preventDefault();

        var input = $(this);
        var button = this;

        button.disabled = true;
        input.html('SAVING...');
        
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_student',
            method: 'POST',
            data: $('#student_form').serialize(),
            success: function(data){
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#student_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_student_branch();
                refresh_student_status();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $('.save_language_student').on('click', function(e){
        e.preventDefault();

        var input = $(this);
        var button = this;

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_language_student',
            method: 'POST',
            data: $('#language_student_form').serialize(),
            success: function(data){
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#student_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_language_student();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $('.save_ssv_student').on('click', function(e){
        e.preventDefault();

        var input = $(this);
        var button = this;

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_ssv_student',
            method: 'POST',
            data: $('#ssv_student_form').serialize(),
            success: function(data){
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#student_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_ssv_student();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Open Student Modal (ADD)
    $('.add_student').on('click', function(){
        $('#add_edit').val('add');
        $('#l_add_edit').val('add');
        $('#s_add_edit').val('add');
        $('#student_type_tab a:first').tab('show');
    });

    //Open Student Modal (EDIT)
    $(document).on('click', '.edit_student', function(){
        $('#student_type_tab a[href="#student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: {id: id},
            dataType: 'json',
            success:function(data){
                $('#add_edit').val('edit');
                $('#id').val(data.id);
                $('#fname').val(data.fname);
                $('#mname').val(data.mname);
                $('#lname').val(data.lname);
                $('#birthdate').val(data.birthdate);
                $('#age').val(data.age);
                $('#contact').val(data.contact);

                if(data.program){
                    $('#program').val(data.program.id).trigger('change');
                }
                if(data.school){
                    $('#school').val(data.school.id).trigger('change');
                }
                if(data.benefactor){
                    $('#benefactor').val(data.benefactor.id).trigger('change');
                }
                
                $('#address').val(data.address);
                $('#email').val(data.email);
                $('#sign_up').val(data.date_of_signup);
                $('#medical').val(data.date_of_medical);
                $('#completion').val(data.date_of_completion);
                $('#referral').val(data.referral.id).trigger('change');
                $('#gender').val(data.gender).trigger('change');
                $('#branch').val(data.branch.id).trigger('change');
                $('#course').val(data.course.id).trigger('change');
                $('#year').val(data.departure_year.id).trigger('change');
                $('#month').val(data.departure_month.id).trigger('change');
                $('#remarks').val(data.remarks);
                $('#student_modal').modal('toggle');
                $('#student_modal').modal('show');
            }
        });
    });

    //Open Language Student Modal (EDIT)
    $(document).on('click', '.edit_language_student', function(){
        $('#student_type_tab a[href="#language_student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: {id: id},
            dataType: 'json',
            success:function(data){
                $('#l_add_edit').val('edit');
                $('#l_id').val(data.id);
                $('#l_fname').val(data.fname);
                $('#l_mname').val(data.mname);
                $('#l_lname').val(data.lname);
                $('#l_birthdate').val(data.birthdate);
                $('#l_age').val(data.age);
                $('#l_contact').val(data.contact);                    
                $('#l_address').val(data.address);
                $('#l_email').val(data.email);
                $('#l_referral').val(data.referral.id).trigger('change');
                $('#l_gender').val(data.gender).trigger('change');
                $('#l_branch').val(data.branch.id).trigger('change');
                $('#l_course').val(data.course.id).trigger('change');
                $('#l_year').val(data.departure_year.id).trigger('change');
                $('#l_remarks').val(data.remarks);
                $('#student_modal').modal('toggle');
                $('#student_modal').modal('show');
            }
        });
    });

    //Open Language Student Modal (EDIT)
    $(document).on('click', '.edit_ssv_student', function(){
        $('#student_type_tab a[href="#ssv_student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: {id: id},
            dataType: 'json',
            success:function(data){
                $('#s_add_edit').val('edit');
                $('#s_id').val(data.id);
                $('#s_fname').val(data.fname);
                $('#s_mname').val(data.mname);
                $('#s_lname').val(data.lname);
                $('#s_birthdate').val(data.birthdate);
                $('#s_age').val(data.age);
                $('#s_contact').val(data.contact);      
                $('#s_program').val(data.program.id).trigger('change');
                $('#s_benefactor').val(data.benefactor.id).trigger('change');              
                $('#s_address').val(data.address);
                $('#s_email').val(data.email);
                $('#s_referral').val(data.referral.id).trigger('change');
                $('#s_gender').val(data.gender).trigger('change');
                $('#s_branch').val(data.branch.id).trigger('change');
                $('#s_course').val(data.course.id).trigger('change');
                $('#s_year').val(data.departure_year.id).trigger('change');
                $('#s_remarks').val(data.remarks);
                $('#student_modal').modal('toggle');
                $('#student_modal').modal('show');
            }
        });
    });

    //Delete Student
    $(document).on('click', '.delete_student', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete a student. This may affect multiple rows',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/delete_student',
                    method: 'get',
                    data: {id:id},
                    type: 'json',
                    success:function(data){
                        swal('Deleted!', 'This Student has been Deleted', 'success');

                        refresh_student_branch();
                        refresh_student_status();
                        refresh_language_student();
                    }
                });
            }
        });
    });

    //Final School
    $(document).on('click', '.final_student', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Go for Final School?',
            text: 'This Student will be in Final School',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    url: '/final_student',
                    method: 'get',
                    data: {id: id},
                    dataType: 'text',
                    success: function(data){
                        swal('Congratulations!', 'This Student is now in Final School!', 'success');

                        refresh_student_branch();
                        refresh_student_status();
                    }
                });
            }
        });
    });

    //Back Out
    $(document).on('click', '.backout_student', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Student will backout?',
            text: 'This Student will be transferred to list of backouts',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    url: '/backout_student',
                    method: 'get',
                    data: {id: id},
                    dataType: 'text',
                    success: function(data){
                        swal('This Student has backed out!', '', 'warning');

                        refresh_student_branch();
                        refresh_student_status();
                        if(current_ssv != ''){
                            refresh_ssv_student();
                            refresh_ssv_backout();
                        }
                    }
                });
            }
        });
    });

    //Continue (From Back Out)
    $(document).on('click', '.continue_student', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Student will apply again?',
            text: 'This Student will be transferred to list of Active Students',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    url: '/continue_student',
                    method: 'get',
                    data: {id: id},
                    dataType: 'text',
                    success: function(data){
                        if(current_status == 'Final School'){
                            swal('Success!', 'This Student out of Final School!', 'success');
                        }
                        else{
                            swal('Success!', 'This Student is now active again!', 'success');
                        }
                        refresh_student_branch();
                        refresh_student_status();
                        if(current_ssv != ''){
                            refresh_ssv_student();
                            refresh_ssv_backout();
                        }
                    }
                });
            }
        });
    });

    //Approve (Only in Result Monitoring)
    $(document).on('click', '.approve_student', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Student COE Approved?',
            text: 'Confirm that this student\'s COE is approved?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    url: '/approve_student',
                    method: 'get',
                    data: {id: id},
                    dataType: 'text',
                    success: function(data){
                        swal('Congratulations!', 'Student COE Approved!', 'success');

                        refresh_student_branch();
                        refresh_student_status();
                        refresh_student_result();
                    }
                });
            }
        });
    });

    //Deny (Only in Result Monitoring)
    $(document).on('click', '.deny_student', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Student COE Denied?',
            text: 'Confirm that this student\'s COE is denied?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    url: '/deny_student',
                    method: 'get',
                    data: {id: id},
                    dataType: 'text',
                    success: function(data){
                        swal('Student COE Denied', '', 'warning');

                        refresh_student_branch();
                        refresh_student_status();
                        refresh_student_result();
                    }
                });
            }
        });
    });

    //Cancel (Only in Result Monitoring)
    $(document).on('click', '.cancel_student', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Student will Cancel?',
            text: 'Confirm that this student decided to cancel?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    url: '/cancel_student',
                    method: 'get',
                    data: {id: id},
                    dataType: 'text',
                    success: function(data){
                        swal('Student Cancelled', '', 'warning');

                        refresh_student_branch();
                        refresh_student_status();
                        refresh_student_result();
                    }
                });
            }
        });
    });

    $(document).on('click', '.view_profile', function(){
        var id = $(this).attr('id');

        $.ajax({
            url: '/view_profile_student/'+id,
            method: 'get',
            dataType: 'JSON',
            success: function(data){
                if(data.mname){
                    $('#p_stud_name').text(data.lname + ', ' + data.fname + ' ' + data.mname);
                }
                else{
                    $('#p_stud_name').text(data.lname + ', ' + data.fname);
                }
                
                if(data.program){
                    if(data.program.name != 'Language Only' && data.program.name != 'SSV (Careworker)' &&
                            data.program.name != 'SSV (Hospitality)'){
                        $('#p_departure').text(data.departure_year.name + ' ' + data.departure_month.name);
                    }
                    else{
                        $('#p_departure').text('N/A');
                    }
                }else{
                    $('#p_departure').text(data.departure_year.name + ' ' + data.departure_month.name);
                }

                $('#p_contact').text(data.contact);
                $('#p_program').text(data.program ? data.program.name : '-');
                $('#p_school').text(data.school ? data.school.name : '-');
                $('#p_benefactor').text(data.benefactor ? data.benefactor.name : '-');
                var age = getAge(data.birthdate);
                $('#p_birthdate').text(data.birthdate + ' (' + age + ')');
                $('#p_gender').text(data.gender);
                $('#p_referral').text(data.referral.fname);
                $('#p_sign_up').text(data.date_of_signup);
                $('#p_medical').text(data.date_of_medical ? data.date_of_medical : '-');
                $('#p_completion').text(data.date_of_completion ? data.date_of_completion : '-');
                $('#p_branch').text(data.branch.name);
                $('#p_status').text(data.status);
                $('#p_coe_status').text(data.coe_status);
                $('#p_email').text(data.email);
                $('#p_course').text(data.course.name);
                $('#p_address').text(data.address);
                $('#p_remarks').text(data.remarks ? data.remarks : '-');
            }
        });
    });        

    //Course Select 2
    $('#course, #l_course, #s_course').select2({
        placeholder: 'Select Course',
        ajax: {
            url: "/courseAll",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
    });

    //Program Select 2
    $('#program').select2({
        allowClear: true,
        placeholder: 'Select Program',
        ajax: {
            url: "/programAll",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
    });

    $('#s_program').select2({
        allowClear: true,
        placeholder: 'Select Program',
        ajax: {
            url: "/programSSV",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
    });

    //School Select 2
    $('#school').select2({
        allowClear: true,
        placeholder: 'Select School',
        ajax: {
            url: "/schoolAll",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
    });

    //Benefactor Select 2
    $('#benefactor, #s_benefactor').select2({
        allowClear: true,
        placeholder: 'Select Benefactor',
        ajax: {
            url: "/benefactorAll",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
    });
    
    function getAge(birthdate){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        today = yyyy + '-' + mm + '-' + dd;
        birth_array = birthdate.split('-');

        var age = yyyy - parseInt(birth_array[0]);

        if(mm == parseInt(birth_array[1])){
            if(dd < birth_array[2]){
                age--;
            }
        }
        else if(mm < parseInt(birth_array[1])){
            age--;
        }

        return age;
    }
    //FUNCTIONS -- END
});