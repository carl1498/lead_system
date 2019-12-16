$(document).ready(function(){

    //INITIALIZE -- START

    var current_branch = 'Makati';
    var current_status = '', current_result = '',
    current_ssw = '', current_titp = '';
    var departure_year, departure_month = $('#month_select').val();
    var get_year, get_month, get_departure;
    var current_tab = 'Branch';
    var current_switch = 'Student';
    var modal_close = true;
    var title;

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

    //Copied from stackoverflow
    function moveScroller() {
        var $anchor = $("#scroller-anchor");
        var $scroller = $('#box-primary-fixed');
    
        var move = function() {
            var st = $(window).scrollTop();
            var ot = $anchor.offset().top;
            if($(window).width() > 991){
                if(st > ot) {
                    $scroller.css({
                        position: "fixed",
                        top: "10px"
                    });
                } else {
                    $scroller.css({
                        position: "relative",
                        top: ""
                    });
                }
            }
        };
        $(window).scroll(move);
        move();
    }

    $(function() {
        moveScroller();
    });
    //Copied from stackoverflow
    
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    $('.select2').select2();
    
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $('#student_modal').on('shown.bs.modal', function(){
        $('#fname').focus();
    });

    $("#student_modal").on("hidden.bs.modal", function(e){
        student_modal_clear();
        $('#add_continuous').bootstrapToggle('off');
        modal_close = true;
    });

    function student_modal_clear(){
        $('#student_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#language_student_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#ssw_student_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#titp_student_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#student_modal').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }
    
    $('#add_continuous').bootstrapToggle('off')

    $('input, select').attr('autocomplete', 'off');

    function disableTabs(){
        $(`li.branch_pick, li.status_pick, li.result_pick, 
        li.language_pick, li.all_pick, li.ssw_pick, li.titp_pick`
        ).addClass('disabled').css('cursor', 'not-allowed');

        $(`a.branch_pick, a.status_pick, a.result_pick,
        a.language_pick, a.all_pick, a.ssw_pick, a.titp_pick`
        ).addClass('disabled').css('pointer-events', 'none');

        $('.switch, .refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.branch_pick, li.status_pick, li.result_pick, 
        li.language_pick, li.all_pick, li.ssw_pick, li.titp_pick`
        ).removeClass('disabled').css('cursor', 'pointer');
        
        $(`a.branch_pick, a.status_pick, a.result_pick,
        a.language_pick, a.all_pick, a.ssw_pick, a.titp_pick`
        ).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
        
        switch(current_switch){
            case 'SSW'  : $('.switch_student, .switch_titp').attr('disabled', false); break;
            case 'Student'  : $('.switch_ssw, .switch_titp').attr('disabled', false); break;
            case 'TITP'  : $('.switch_student, .switch_ssw').attr('disabled', false); break;
        }
    }

    function refresh(){
        disableTabs();
        get_year = $("#year_select option:selected").text();
        get_month = $("#month_select option:selected").text();
        update_buttons();

        if(current_tab == 'Branch' || current_tab == 'Status' || current_tab == 'Result'){
            showYearSelect();
            showMonthSelect();
        }else if(current_tab == 'Language'){
            showYearSelect();
            $('.month_select').hide();
            $('#month_select').next(".select2-container").hide();
            $('.select_description').text('Year:');
        }else if(current_tab == 'All'){
            $('.year_select').hide();
            $('#year_select').next(".select2-container").hide();
            $('.month_select').hide();
            $('#month_select').next(".select2-container").hide();
            $('.select_description').text('');
        }else if(current_tab == 'SSW'){
            showYearSelect();
            $('.month_select').hide();
            $('#month_select').next(".select2-container").hide();
            $('.select_description').text('Year:');
        }else if(current_tab == 'TITP'){
            showYearSelect();
            showMonthSelect();
            $('.select_description').text('Departure:');
        }
        
        switch(current_tab){
            case 'Branch'   : refresh_student_branch(); break;
            case 'Status'   : refresh_student_status(); break;
            case 'Result'   : refresh_student_result(); break;
            case 'Language' : refresh_language_student(); break;
            case 'SSW'      : refresh_ssw_student(); break;
            case 'TITP'     : refresh_titp_student(); break;
            case 'All'      : refresh_all_student(); break;
        }
    }
    
    $('.refresh_table').on('click', function(){
        refresh();
    });

    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        if(modal_close == true){
            refresh();

            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }
    });

    //COLUMNS & COLUMNDEFS
    var columns_students = [
        {data: 'name', name: 'name'},
        {data: 'contact', name: 'contact'},
        {data: 'program.name', name: 'program'},
        {data: 'school.name', name: 'school'},
        {data: 'benefactor.name', name: 'benefactor'},
        {data: 'gender', name: 'gender'},
        {data: 'birthdate', name: 'birthdate'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'date_of_signup', name: 'date_of_signup'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'status', name: 'status'},
        {data: 'coe_status', name: 'coe_status'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]

    var columns_students_status = [
        {data: 'name', name: 'name'},
        {data: 'branch.name', name: 'branch'},
        {data: 'contact', name: 'contact'},
        {data: 'program.name', name: 'program'},
        {data: 'school.name', name: 'school'},
        {data: 'benefactor.name', name: 'benefactor'},
        {data: 'gender', name: 'gender'},
        {data: 'birthdate', name: 'birthdate'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'date_of_signup', name: 'date_of_signup'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'status', name: 'status'},
        {data: 'coe_status', name: 'coe_status'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]

    var columns_students_result = [
        {data: 'name', name: 'name'},
        {data: 'branch.name', name: 'branch'},
        {data: 'program.name', name: 'program'},
        {data: 'school.name', name: 'school'},
        {data: 'status', name: 'status'},
        {data: 'coe_status', name: 'coe_status'},
        {data: 'referral.fname', name: 'referral'},
        {data: "action", orderable:false,searchable:false}
    ]

    var columns_language_students = [
        {data: 'name', name: 'name'},
        {data: 'branch.name', name: 'branch'},
        {data: 'contact', name: 'contact'},
        {data: 'gender', name: 'gender'},
        {data: 'birthdate', name: 'birthdate'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'date_of_signup', name: 'date_of_signup'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]
    var columns_all_students = columns_students_status;

    var columns_ssw_students = [
        {data: 'name', name: 'name'},
        {data: 'contact', name: 'contact'},
        {data: 'gender', name: 'gender'},
        {data: 'birthdate', name: 'birthdate'},
        {data: 'program.name', name: 'program'},
        {data: 'benefactor.name', name: 'benefactor'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'date_of_signup', name: 'date_of_signup'},
        {data: 'referral.fname', name: 'referral'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]

    var columns_titp_students = [
        {data: 'name', name: 'name'},
        {data: 'program.name', name: 'program'},
        {data: 'contact', name: 'contact'},
        {data: 'company.name', name: 'company'},
        {data: 'gender', name: 'gender'},
        {data: 'birthdate', name: 'birthdate'},
        {data: 'course.name', name: 'course'},
        {data: 'email', name: 'email'},
        {data: 'coe_status', name: 'coe_status'},
        {data: 'remarks', name: 'remarks'},
        {data: "action", orderable:false,searchable:false}
    ]

    //DATATABLES EXCEL

    function update_buttons(){
        
        switch(current_tab){
            case 'Branch'   : title = current_branch; break;
            case 'Status'   : title = current_status; break;
            case 'Result'   : title = current_result; break;
            case 'Language' : title = current_tab; break;
            case 'SSW'      : 
                switch(current_ssw){
                    case 'SSW'      : title = current_ssw; break;
                    case 'Back Out' : title = 'SSW ' + current_ssw; break;
                }
            case 'TITP'  : 
                switch(current_ssw){
                    case 'TITP'  : title = current_ssw; break;
                    case 'Back Out' : title = 'SSW ' + current_ssw; break;
                }
        }

        get_departure = (get_year == 'All' && get_month == 'All') ? 'All' : get_year + ' ' + get_month;

        buttons_format = [//for branches, final school, back out, result monitoring, titp
            {extend: 'excelHtml5', title: title + ' - ' + get_departure,
            exportOptions: {
                columns: ':visible'
            }},
            'colvis'
        ];
        
        year_buttons_format = [//for language, ssw
            {extend: 'excelHtml5', title: title + ' - ' + get_year,
            exportOptions: {
                columns: ':visible'
            }},
            'colvis'
        ];

        all_buttons_format = [
            {extend: 'excelHtml5', title: 'All',
            exportOptions: {
                columns: ':visible'
            }},
            'colvis'
        ];
    }

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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: buttons_format,
            responsive: true,
            ajax: {
                url: '/student_branch',
                data: {
                    current_branch: current_branch,
                    departure_year: departure_year,
                    departure_month: departure_month
                }
            },
            columnDefs: [{defaultContent: "", targets: "_all"}],
            columns: columns_students,
            order: [[3,'asc']]
        });
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: buttons_format,
            responsive: true,
            ajax: {
                url: '/student_status',
                data: {
                    current_status: current_status,
                    departure_year: departure_year,
                    departure_month: departure_month
                }
            },
            columnDefs: [{defaultContent: "", targets: "_all"}],
            columns: columns_students_status,
            order: [[4,'asc']]
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: buttons_format,
            responsive: true,
            ajax: {
                url: '/student_result',
                data: {
                    current_result: current_result,
                    departure_year: departure_year,
                    departure_month: departure_month
                }
            },
            columnDefs: [{defaultContent: "", targets: "_all"}],
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: year_buttons_format,
            responsive: true,
            ajax: {
                url: '/language_student',
                data: {
                    departure_year: departure_year
                }
            },
            columnDefs: [{defaultContent: "", targets: "_all"}],
            columns: columns_language_students,
        });
    }

    function refresh_all_student(){

        all_students = $('#all_students').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: all_buttons_format,
            responsive: true,
            ajax: {
                url: '/all_student',
                data: {}
            },
            columnDefs: [{defaultContent: "", targets: "_all"}],
            columns: columns_all_students,
        });
    }

    function refresh_ssw_student(){
        
        departure_year = $('#year_select').val();

        ssw_students = $('#ssw_students').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: year_buttons_format,
            responsive: true,
            ajax: {
                url: '/ssw_student',
                data: {
                    departure_year: departure_year,
                    current_ssw: current_ssw
                }
            },
            columnDefs: [{defaultContent: "", targets: "_all"}],
            columns: columns_ssw_students,
            order: [[4,'asc']]
        });
    }

    function refresh_titp_student(){
        
        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        titp_students = $('#titp_students').DataTable({
            stateSave: true,
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: buttons_format,
            responsive: true,
            ajax: {
                url: '/titp_student',
                data: {
                    departure_year: departure_year,
                    departure_month: departure_month,
                    current_titp: current_titp
                }
            },
            columnDefs: [{defaultContent: "", targets: "_all"}],
            columns: columns_titp_students,
            order: [[1,'asc']]
        });
    }

    //DATATABLES -- END

    //FUNCTIONS -- START
    
    $('.switch').on('click', function(){
        if($(this).find('.switch_name').text() == 'Student'){
            $('.ssw_pick, .titp_pick').hide();
            $('.branch_pick, .status_pick, .result_pick, .language_pick').show();
            $('#student_list_tab #student_first').click();
            current_switch = 'Student';
        }
        else if($(this).find('.switch_name').text() == 'SSW'){
            $('.branch_pick, .status_pick, .result_pick, .language_pick, .titp_pick').hide();
            $('.ssw_pick').show();
            $('#student_list_tab #ssw_first').click();
            current_switch = 'SSW';
        }
        else if($(this).find('.switch_name').text() == 'TITP'){
            $('.branch_pick, .status_pick, .result_pick, .language_pick, .ssw_pick').hide();
            $('.titp_pick').show();
            $('#student_list_tab #titp_first').click();
            current_switch = 'TITP';
        }
        disableTabs();
    });

    function showYearSelect(){
        $('.year_select').show();
        $('#year_select').next(".select2-container").show();
    }

    function showMonthSelect(){
        $('.month_select').show();
        $('#month_select').next(".select2-container").show();
        $('.select_description').text('Departure:');
    }

    $('.branch_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_tab = 'Branch';
            current_branch = $(this).text();
        }
    });

    $('.status_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_tab = 'Status';
            current_status = $(this).text();
        }
    });

    $('.result_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_tab = 'Result';
            current_result = $(this).text();
        }
    });

    $('.language_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){current_tab = 'Language';}
    });

    $('.all_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){current_tab = 'All';}
    });

    $('.ssw_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_ssw = $(this).text();

            current_tab = 'SSW';
        }
    });

    $('.titp_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_titp = $(this).text();

            current_tab = 'TITP';
        }
    })

    $(document).on('change', '#year_select, #month_select', function(){
        refresh();
    });

    $(document).on('submit', '#student_form', function(e){
        e.preventDefault();

        var input = $('.save_student');
        var button = document.getElementsByClassName("save_student")[0];

        button.disabled = true;
        input.html('SAVING...');

        var formData = new FormData($(this)[0]);
        
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_student',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                if(data == false){
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if($('#add_continuous').is(':checked')){
                    student_modal_clear();
                    add_edit_init();
                }
                else{
                    $('#student_modal').modal('hide');
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#language_student_form', function(e){
        e.preventDefault();

        var input = $('.save_language_student');
        var button = document.getElementsByClassName("save_language_student")[0];

        button.disabled = true;
        input.html('SAVING...');

        var formData = new FormData($(this)[0]);

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_language_student',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                if(data == false){
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if($('#add_continuous').is(':checked')){
                    student_modal_clear();
                    add_edit_init();
                }
                else{
                    $('#student_modal').modal('hide')
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#ssw_student_form', function(e){
        e.preventDefault();

        var input = $('.save_ssw_student');
        var button = document.getElementsByClassName("save_ssw_student")[0];

        button.disabled = true;
        input.html('SAVING...');

        var formData = new FormData($(this)[0]);

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_ssw_student',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                if(data == false){
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if($('#add_continuous').is(':checked')){
                    student_modal_clear();
                    add_edit_init();
                }
                else{
                    $('#student_modal').modal('hide')
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#titp_student_form', function(e){
        e.preventDefault();

        var input = $('.save_titp_student');
        var button = document.getElementsByClassName("save_titp_student")[0];

        button.disabled = true;
        input.html('SAVING...');

        var formData = new FormData($(this)[0]);

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_titp_student',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                if(data == false){
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if($('#add_continuous').is(':checked')){
                    student_modal_clear();
                    add_edit_init();
                }
                else{
                    $('#student_modal').modal('hide')
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Open Student Modal (ADD)
    $('.add_student').on('click', function(){
        modal_close = false;
        add_edit_init();
        $('#student_type_tab a:first').tab('show');
        $('#student_modal').modal('toggle');
        $('#student_modal').modal('show');
    });

    function add_edit_init(){
        $('#add_edit').val('add');
        $('#l_add_edit').val('add');
        $('#s_add_edit').val('add');
        $('#t_add_edit').val('add');
    }

    //Open Student Modal (EDIT)
    $(document).on('click', '.edit_student', function(){
        modal_close = false;
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
                $('#civil').val(data.civil_status).trigger('change');
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
        modal_close = false;
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
                $('#l_civil').val(data.civil_status).trigger('change');
                $('#l_contact').val(data.contact);                    
                $('#l_address').val(data.address);
                $('#l_email').val(data.email);
                $('#l_sign_up').val(data.date_of_signup);
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
    $(document).on('click', '.edit_ssw_student', function(){
        modal_close = false;
        $('#student_type_tab a[href="#ssw_student_form"]').tab('show');
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
                $('#s_civil').val(data.civil_status).trigger('change');
                $('#s_contact').val(data.contact);      
                if(data.program){
                    $('#s_program').val(data.program.id).trigger('change'); 
                }
                if(data.benefactor){
                    $('#s_benefactor').val(data.benefactor.id).trigger('change');   
                }
                $('#s_address').val(data.address);
                $('#s_email').val(data.email);
                $('#s_sign_up').val(data.date_of_signup);
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

    //Open TITP Student Modal (EDIT)
    $(document).on('click', '.edit_titp_student', function(){
        modal_close = false;
        $('#student_type_tab a[href="#titp_student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: {id: id},
            dataType: 'json',
            success:function(data){
                $('#t_add_edit').val('edit');
                $('#t_id').val(data.id);
                $('#t_fname').val(data.fname);
                $('#t_mname').val(data.mname);
                $('#t_lname').val(data.lname);
                $('#t_program').val(data.program.id).trigger('change');   
                if(data.company){
                    $('#t_company').val(data.company.id).trigger('change');   
                }
                $('#t_contact').val(data.contact);
                $('#t_gender').val(data.gender).trigger('change');
                $('#t_birthdate').val(data.birthdate);
                $('#t_civil').val(data.civil_status).trigger('change');
                $('#t_course').val(data.course.id).trigger('change');
                $('#t_email').val(data.email);
                $('#t_address').val(data.address);
                $('#t_year').val(data.departure_year.id).trigger('change');
                $('#t_month').val(data.departure_month.id).trigger('change');
                $('#t_remarks').val(data.remarks);
                $('#student_modal').modal('toggle');
                $('#student_modal').modal('show');
            }
        });
    });

    //Delete Student
    $(document).on('click', '.delete_student', function(){
        var id = $(this).attr('id');

        swal.fire({
            title: 'Confirm User',
            text: 'For security purposes, input your password again.',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/confirm_user',
                    data: { password:password },
                    method: 'POST',
                    success: function(data){
                        if(data == 0){
                            swal('Password Incorrect!', 'Please try again', 'error');
                            return;
                        }
                        else{
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
                                        data: {
                                            id:id,
                                            password:password
                                        },
                                        type: 'json',
                                        success:function(data){
                                            notif('Success!', 'This Student has been Deleted', 'success', 'glyphicon-ok');
                    
                                            refresh();
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            },
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
                        notif('Success!', 'This Student is now in Final School!', 'success', 'glyphicon-ok');
                        view_profile(data);

                        refresh();
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
                        notif('This Student has backed out!', '', 'warning', 'glyphicon-warning-sign');
                        view_profile(data);

                        refresh();
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
                            notif('Success!', 'This Student is out of Final School!', 'success', 'glyphicon-ok');
                        }
                        else{
                            notif('Success!', 'This Student is now active again!', 'success', 'glyphicon-ok');
                        }
                        view_profile(data);
                        
                        refresh();
                    }
                });
            }
        });
    });

    //Approve (Only in Result Monitoring)
    $(document).on('click', '.approve_student', function(){
        var id = $(this).attr('id');

        title = 'Student COE Approved?';
        text = 'Confirm that this student\'s COE is approved?';

        if(current_tab == 'TITP'){
            title = 'Student has Passed?';
            text = 'Confirm that this trainee has passed?';
        }

        swal({
            title: title,
            text: text,
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
                        notif('Congratulations!', 'Student COE Approved!', 'success', 'glyphicon-ok');
                        view_profile(data);

                        refresh();
                    }
                });
            }
        });
    });

    //Deny (Only in Result Monitoring)
    $(document).on('click', '.deny_student', function(){
        var id = $(this).attr('id');

        title = 'Student COE Denied?';
        text = 'Confirm that this student\'s COE is denied?';
        
        if(current_tab == 'TITP'){
            title = 'Student has Failed?';
            text = 'Confirm that this trainee has failed?';
        }

        swal({
            title: title,
            text: text,
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
                        notif('Student COE Denied', '', 'warning', 'glyphicon-warning-sign');
                        view_profile(data);

                        refresh();
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
                        notif('Student Cancelled', '', 'warning', 'glyphicon-warning-sign');
                        view_profile(data);

                        refresh();
                    }
                });
            }
        });
    });

    $(document).on('click', '.view_profile', function(){
        var id = $(this).attr('id');

        view_profile(id);
    });        

    //Course Select 2
    $('#course, #l_course, #s_course, #t_course').select2({
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
            url: "/programSSW",
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

    $('#t_program').select2({
        allowClear: true,
        placeholder: 'Select Program',
        ajax: {
            url: "/programTITP",
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

    //Company Select 2
    $('#t_company').select2({
        allowClear: true,
        placeholder: 'Select Company',
        ajax: {
            url: "/companyAll",
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

    function view_profile(id){
        $.ajax({
            url: '/view_profile_student/'+id,
            method: 'get',
            dataType: 'JSON',
            success: function(data){
                $('#p_picture').attr('src', './storage/img/student/'+data.picture);
                
                if(data.mname){
                    $('#p_stud_name').text(data.lname + ', ' + data.fname + ' ' + data.mname);
                }
                else{
                    $('#p_stud_name').text(data.lname + ', ' + data.fname);
                }
                
                if(data.program){
                    if(data.program.name != 'Language Only' && data.program.name != 'SSW (Careworker)' &&
                            data.program.name != 'SSW (Hospitality)'){
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
                $('#p_company').text(data.company ? data.company.name : '-');
                $('#p_birthdate').text(data.birthdate + ' (' + data.age + ')');
                $('#p_civil').text(data.civil_status);
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
                $('.print_student_profile').attr('id', data.id);
                $('.print_student_profile').removeAttr('disabled');
            }
        });
    }

    $(document).on('click', '.print_student_profile', function(){
        if(!$(this).hasClass('disabled')){
            let id = $(this).attr('id');

            window.open('/studentPDF/'+id, '_blank');
        }
    });

    //FUNCTIONS -- END
});