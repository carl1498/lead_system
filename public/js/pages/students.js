/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/students.js":
/***/ (function(module, exports) {

$(document).ready(function () {

    //INITIALIZE -- START

    var current_branch = 'Makati';
    var current_status = '',
        current_result = '',
        current_ssw = '',
        current_titp = '',
        current_intern = '';
    var departure_year,
        departure_month = $('#month_select').val(),
        batch = 'All';
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
        success: function success(data) {
            $('#year_select').val(data).trigger('change');
            departure_year = $('#year_select').val();
        }
    });

    //Copied from stackoverflow
    function moveScroller() {
        var $anchor = $("#scroller-anchor");
        var $scroller = $('#box-primary-fixed');

        var move = function move() {
            var st = $(window).scrollTop();
            var ot = $anchor.offset().top;
            if ($(window).width() > 991) {
                if (st > ot) {
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

    $(function () {
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
        trigger: 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $('#student_modal').on('shown.bs.modal', function () {
        $('#fname').focus();
    });

    $("#student_modal").on("hidden.bs.modal", function (e) {
        student_modal_clear();
        $('#add_continuous').bootstrapToggle('off');
        modal_close = true;
    });

    $('#emergency_modal, #emp_history_modal, #educational_background_modal').on('hidden.bs.modal', function (e) {
        student_other_modal_clear();
        setTimeout(function () {
            $('#student_info_modal').modal('show');
        }, 500);
        $('#emp_continuous, #emergency_continuous, #educ_continuous').bootstrapToggle('off');
    });

    function student_other_modal_clear() {
        $('#emergency_modal, #emp_history_modal, #educational_background_modal').find("input,textarea,select").val('').end();
        $('#eb_add_edit, #e_add_edit, #eh_add_edit').val('add');
    }

    function student_modal_clear() {
        $('#student_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#language_student_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#ssw_student_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#titp_student_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#intern_student_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#student_modal').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#add_continuous, #emp_continuous, #emergency_continuous, #educ_continuous').bootstrapToggle('off');

    $('input, select').attr('autocomplete', 'off');

    function disableTabs() {
        $('li.branch_pick, li.status_pick, li.result_pick, \n        li.language_pick, li.all_pick, li.ssw_pick, li.titp_pick,\n        li.intern_pick').addClass('disabled').css('cursor', 'not-allowed');

        $('a.branch_pick, a.status_pick, a.result_pick,\n        a.language_pick, a.all_pick, a.ssw_pick, a.titp_pick,\n        a.intern_pick').addClass('disabled').css('pointer-events', 'none');

        $('.switch, .refresh_table').attr('disabled', true);
    }

    function enableTabs() {
        $('li.branch_pick, li.status_pick, li.result_pick, \n        li.language_pick, li.all_pick, li.ssw_pick, li.titp_pick,\n        li.intern_pick').removeClass('disabled').css('cursor', 'pointer');

        $('a.branch_pick, a.status_pick, a.result_pick,\n        a.language_pick, a.all_pick, a.ssw_pick, a.titp_pick,\n        a.intern_pick').removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);

        switch (current_switch) {
            case 'SSW':
                $('.switch_student, .switch_titp, .switch_intern').attr('disabled', false);break;
            case 'Student':
                $('.switch_ssw, .switch_titp, .switch_intern').attr('disabled', false);break;
            case 'TITP':
                $('.switch_student, .switch_ssw, .switch_intern').attr('disabled', false);break;
            case 'Intern':
                $('.switch_student, .switch_ssw, .switch_titp').attr('disabled', false);break;
        }
    }

    function refresh() {
        disableTabs();
        get_year = $("#year_select option:selected").text();
        get_month = $("#month_select option:selected").text();
        update_buttons();

        if (current_tab == 'Branch' || current_tab == 'Status' || current_tab == 'Result') {
            showYearSelect();
            showMonthSelect();
        } else if (current_tab == 'Language') {
            showYearSelect();
            $('.month_select').hide();
            $('#month_select').next(".select2-container").hide();
            $('.select_description').text('Year:');
        } else if (current_tab == 'All') {
            $('.year_select').hide();
            $('#year_select').next(".select2-container").hide();
            $('.month_select').hide();
            $('#month_select').next(".select2-container").hide();
            $('.select_description').text('');
        } else if (current_tab == 'SSW') {
            showYearSelect();
            showBatchSelect();
            $('.month_select').hide();
            $('#month_select').next(".select2-container").hide();
            $('.select_description').text('Year:');
        } else if (current_tab == 'TITP') {
            showYearSelect();
            showMonthSelect();
            $('.select_description').text('Departure:');
        }

        if (current_tab != 'SSW') {
            hideBatchSelect();
        }

        switch (current_tab) {
            case 'Branch':
                refresh_student_branch();break;
            case 'Status':
                refresh_student_status();break;
            case 'Result':
                refresh_student_result();break;
            case 'Language':
                refresh_language_student();break;
            case 'SSW':
                refresh_ssw_student();break;
            case 'TITP':
                refresh_titp_student();break;
            case 'Intern':
                refresh_intern_student();break;
            case 'All':
                refresh_all_student();break;
        }
    }

    $('.refresh_table').on('click', function () {
        refresh();
    });

    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        if (modal_close == true) {
            refresh();

            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }
    });

    //COLUMNS & COLUMNDEFS
    var columns_students = [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'program.name', name: 'program' }, { data: 'school', name: 'school' }, { data: 'benefactor', name: 'benefactor' }, { data: 'gender', name: 'gender' }, { data: 'birthdate', name: 'birthdate' }, { data: 'course.name', name: 'course' }, { data: 'email', name: 'email' }, { data: 'date_of_signup', name: 'date_of_signup' }, { data: 'referral.fname', name: 'referral' }, { data: 'status', name: 'status' }, { data: 'coe_status', name: 'coe_status' }, { data: 'remarks', name: 'remarks' }, { data: "action", orderable: false, searchable: false }];

    var columns_students_status = [{ data: 'name', name: 'name' }, { data: 'branch.name', name: 'branch' }, { data: 'contact', name: 'contact' }, { data: 'program.name', name: 'program' }, { data: 'school', name: 'school' }, { data: 'benefactor', name: 'benefactor' }, { data: 'gender', name: 'gender' }, { data: 'birthdate', name: 'birthdate' }, { data: 'course.name', name: 'course' }, { data: 'email', name: 'email' }, { data: 'date_of_signup', name: 'date_of_signup' }, { data: 'referral.fname', name: 'referral' }, { data: 'status', name: 'status' }, { data: 'coe_status', name: 'coe_status' }, { data: 'remarks', name: 'remarks' }, { data: "action", orderable: false, searchable: false }];

    var columns_students_result = [{ data: 'name', name: 'name' }, { data: 'branch.name', name: 'branch' }, { data: 'program.name', name: 'program' }, { data: 'school', name: 'school' }, { data: 'status', name: 'status' }, { data: 'coe_status', name: 'coe_status' }, { data: 'referral.fname', name: 'referral' }, { data: "action", orderable: false, searchable: false }];

    var columns_language_students = [{ data: 'name', name: 'name' }, { data: 'branch.name', name: 'branch' }, { data: 'contact', name: 'contact' }, { data: 'gender', name: 'gender' }, { data: 'birthdate', name: 'birthdate' }, { data: 'course.name', name: 'course' }, { data: 'email', name: 'email' }, { data: 'date_of_signup', name: 'date_of_signup' }, { data: 'referral.fname', name: 'referral' }, { data: 'remarks', name: 'remarks' }, { data: "action", orderable: false, searchable: false }];
    var columns_all_students = columns_students_status;

    var columns_ssw_students = [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'gender', name: 'gender' }, { data: 'birthdate', name: 'birthdate' }, { data: 'batch', name: 'batch' }, { data: 'program.name', name: 'program' }, { data: 'benefactor', name: 'benefactor' }, { data: 'course.name', name: 'course' }, { data: 'email', name: 'email' }, { data: 'date_of_signup', name: 'date_of_signup' }, { data: 'referral.fname', name: 'referral' }, { data: 'remarks', name: 'remarks' }, { data: "action", orderable: false, searchable: false }];

    var columns_titp_students = [{ data: 'name', name: 'name' }, { data: 'program.name', name: 'program' }, { data: 'contact', name: 'contact' }, { data: 'company', name: 'company' }, { data: 'gender', name: 'gender' }, { data: 'birthdate', name: 'birthdate' }, { data: 'course.name', name: 'course' }, { data: 'email', name: 'email' }, { data: 'coe_status', name: 'coe_status' }, { data: 'remarks', name: 'remarks' }, { data: "action", orderable: false, searchable: false }];

    var columns_intern_students = [{ data: 'name', name: 'name' }, { data: 'branch.name', name: 'branch' }, { data: 'contact', name: 'contact' }, { data: 'program.name', name: 'program' }, { data: 'benefactor', name: 'benefactor' }, { data: 'university.name', name: 'university' }, { data: 'gender', name: 'gender' }, { data: 'birthdate', name: 'birthdate' }, { data: 'course.name', name: 'course' }, { data: 'email', name: 'email' }, { data: 'remarks', name: 'remarks' }, { data: "action", orderable: false, searchable: false }];

    //DATATABLES EXCEL

    function update_buttons() {

        switch (current_tab) {
            case 'Branch':
                title = current_branch;break;
            case 'Status':
                title = current_status;break;
            case 'Result':
                title = current_result;break;
            case 'Language':
                title = current_tab;break;
            case 'SSW':
                switch (current_ssw) {
                    case 'SSW':
                        title = current_ssw;break;
                    case 'Back Out':
                        title = 'SSW ' + current_ssw;break;
                }
            case 'TITP':
                switch (current_titp) {
                    case 'TITP':
                        title = current_titp;break;
                    case 'Back Out':
                        title = 'TITP ' + current_titp;break;
                }
            case 'Intern':
                switch (current_intern) {
                    case 'Intern':
                        title = current_intern;break;
                    case 'Back Out':
                        title = 'Intern ' + current_intern;break;
                }
        }

        get_departure = get_year == 'All' && get_month == 'All' ? 'All' : get_year + ' ' + get_month;

        buttons_format = [//for branches, final school, back out, result monitoring, titp
        { extend: 'excelHtml5', title: title + ' - ' + get_departure,
            exportOptions: {
                columns: ':visible'
            } }, 'colvis'];

        year_buttons_format = [//for language, ssw
        { extend: 'excelHtml5', title: title + ' - ' + get_year,
            exportOptions: {
                columns: ':visible'
            } }, 'colvis'];

        all_buttons_format = [{ extend: 'excelHtml5', title: 'All',
            exportOptions: {
                columns: ':visible'
            } }, 'colvis'];
    }

    function refresh_student_branch() {
        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        students_branch = $('#students_branch').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_students,
            order: [[3, 'asc']]
        });
    }

    function refresh_student_status() {

        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        students_status = $('#students_status').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_students_status,
            order: [[4, 'asc']]
        });
    }

    function refresh_student_result() {

        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        students_result = $('#students_result').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_students_result,
            order: [[3, 'asc']]
        });
    }

    function refresh_language_student() {

        departure_year = $('#year_select').val();

        language_students = $('#language_students').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_language_students
        });
    }

    function refresh_all_student() {

        all_students = $('#all_students').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_all_students
        });
    }

    function refresh_ssw_student() {

        departure_year = $('#year_select').val();
        var batch = $('#batch_select').val();

        ssw_students = $('#ssw_students').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
                    current_ssw: current_ssw,
                    batch: batch
                }
            },
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_ssw_students,
            order: [[4, 'asc']]
        });
    }

    function refresh_titp_student() {

        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        titp_students = $('#titp_students').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_titp_students,
            order: [[1, 'asc']]
        });
    }

    function refresh_intern_student() {

        departure_year = $('#year_select').val();
        departure_month = $('#month_select').val();

        intern_students = $('#intern_students').DataTable({
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            initComplete: function initComplete(settings, json) {
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
                url: '/intern_student',
                data: {
                    departure_year: departure_year,
                    departure_month: departure_month,
                    current_intern: current_intern
                }
            },
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_intern_students,
            order: [[1, 'asc']]
        });
    }

    function refresh_student_info(id) {
        var student_emergency_table = $('#student_emergency_table').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            destroy: true,
            ajax: '/view_student_emergency/' + id,
            columns: [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'relationship', name: 'relationship' }, { data: 'address', name: 'address' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });

        var student_employment_table = $('#student_employment_table').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            destroy: true,
            ajax: '/view_student_employment/' + id,
            columns: [{ data: 'name', name: 'name' }, { data: 'position', name: 'position' }, { data: 'start', name: 'start' }, { data: 'finished', name: 'finished' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });

        var student_educational_background_table = $('#student_educational_background_table').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            destroy: true,
            ajax: '/view_student_education/' + id,
            columns: [{ data: 'name', name: 'name' }, { data: 'course', name: 'course' }, { data: 'level', name: 'level' }, { data: 'start', name: 'start' }, { data: 'end', name: 'end' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });
    }

    //DATATABLES -- END

    //FUNCTIONS -- START

    $('.switch').on('click', function () {
        if ($(this).find('.switch_name').text() == 'Student') {
            $('.ssw_pick, .titp_pick, .intern_pick').hide();
            $('.branch_pick, .status_pick, .result_pick, .language_pick').show();
            $('#student_list_tab #student_first').click();
            current_switch = 'Student';
        } else if ($(this).find('.switch_name').text() == 'SSW') {
            $('.branch_pick, .status_pick, .result_pick, .language_pick, .titp_pick, .intern_pick').hide();
            $('.ssw_pick').show();
            $('#student_list_tab #ssw_first').click();
            current_switch = 'SSW';
        } else if ($(this).find('.switch_name').text() == 'TITP') {
            $('.branch_pick, .status_pick, .result_pick, .language_pick, .ssw_pick, .intern_pick').hide();
            $('.titp_pick').show();
            $('#student_list_tab #titp_first').click();
            current_switch = 'TITP';
        } else if ($(this).find('.switch_name').text() == 'Intern') {
            $('.branch_pick, .status_pick, .result_pick, .language_pick, .ssw_pick, .titp_pick').hide();
            $('.intern_pick').show();
            $('#student_list_tab #intern_first').click();
            current_switch = 'Intern';
        }

        disableTabs();
    });

    function showYearSelect() {
        $('.year_select').show();
        $('#year_select').next(".select2-container").show();
    }

    function showMonthSelect() {
        $('.month_select').show();
        $('#month_select').next(".select2-container").show();
        $('.select_description').text('Departure:');
    }

    function showBatchSelect() {
        $('#batch_select').show();
        $('#batch_select').next(".select2-container").show();
        $('.batch_description').text('Batch:');
    }

    function hideBatchSelect() {
        $('#batch_select').hide();
        $('#batch_select').next(".select2-container").hide();
        $('.batch_description').text('');
    }

    $('.branch_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_tab = 'Branch';
            current_branch = $(this).text();
        }
    });

    $('.status_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_tab = 'Status';
            current_status = $(this).text();
        }
    });

    $('.result_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_tab = 'Result';
            current_result = $(this).text();
        }
    });

    $('.language_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_tab = 'Language';
        }
    });

    $('.all_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_tab = 'All';
        }
    });

    $('.ssw_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_ssw = $(this).text();

            current_tab = 'SSW';
        }
    });

    $('.titp_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_titp = $(this).text();

            current_tab = 'TITP';
        }
    });

    $('.intern_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_intern = $(this).text();

            current_tab = 'Intern';
        }
    });

    $(document).on('change', '#year_select, #month_select, #batch_select', function () {
        refresh();
    });

    $(document).on('submit', '#student_form', function (e) {
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
            success: function success(data) {
                if (data == false) {
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if ($('#add_continuous').is(':checked')) {
                    student_modal_clear();
                    add_edit_init();
                } else {
                    $('#student_modal').modal('hide');
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#language_student_form', function (e) {
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
            success: function success(data) {
                if (data == false) {
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if ($('#add_continuous').is(':checked')) {
                    student_modal_clear();
                    add_edit_init();
                } else {
                    $('#student_modal').modal('hide');
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#ssw_student_form', function (e) {
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
            success: function success(data) {
                if (data == false) {
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if ($('#add_continuous').is(':checked')) {
                    student_modal_clear();
                    add_edit_init();
                } else {
                    $('#student_modal').modal('hide');
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#titp_student_form', function (e) {
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
            success: function success(data) {
                if (data == false) {
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if ($('#add_continuous').is(':checked')) {
                    student_modal_clear();
                    add_edit_init();
                } else {
                    $('#student_modal').modal('hide');
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#intern_student_form', function (e) {
        e.preventDefault();

        var input = $('.save_intern_student');
        var button = document.getElementsByClassName("save_intern_student")[0];

        button.disabled = true;
        input.html('SAVING...');

        var formData = new FormData($(this)[0]);

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_intern_student',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function success(data) {
                if (data == false) {
                    swal('Error!', 'File/Image format must only be .jpg | .png | .jpeg', 'error');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                if ($('#add_continuous').is(':checked')) {
                    student_modal_clear();
                    add_edit_init();
                } else {
                    $('#student_modal').modal('hide');
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Open Student Modal (ADD)
    $('.add_student').on('click', function () {
        modal_close = false;
        add_edit_init();
        $('#student_type_tab a:first').tab('show');
        $('#student_modal').modal('toggle');
        $('#student_modal').modal('show');
    });

    function add_edit_init() {
        $('#add_edit, #l_add_edit, #s_add_edit, #t_add_edit, #i_add_edit').val('add');
    }

    //Open Student Modal (EDIT)
    $(document).on('click', '.edit_student', function () {
        modal_close = false;
        $('#student_type_tab a[href="#student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: { id: id },
            dataType: 'json',
            success: function success(data) {
                $('#add_edit').val('edit');
                $('#id').val(data.id);
                $('#fname').val(data.fname);
                $('#mname').val(data.mname);
                $('#lname').val(data.lname);
                $('#birthdate').val(data.birthdate);
                $('#civil').val(data.civil_status).trigger('change');
                $('#contact').val(data.contact);

                if (data.program) {
                    $('#program').val(data.program.id).trigger('change');
                }
                if (data.school) {
                    $('#school').val(data.school.id).trigger('change');
                }
                if (data.benefactor) {
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
    $(document).on('click', '.edit_language_student', function () {
        modal_close = false;
        $('#student_type_tab a[href="#language_student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: { id: id },
            dataType: 'json',
            success: function success(data) {
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
    $(document).on('click', '.edit_ssw_student', function () {
        modal_close = false;
        $('#student_type_tab a[href="#ssw_student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: { id: id },
            dataType: 'json',
            success: function success(data) {
                $('#s_add_edit').val('edit');
                $('#s_id').val(data.id);
                $('#s_fname').val(data.fname);
                $('#s_mname').val(data.mname);
                $('#s_lname').val(data.lname);
                $('#s_birthdate').val(data.birthdate);
                $('#s_civil').val(data.civil_status).trigger('change');
                $('#s_contact').val(data.contact);
                if (data.program) {
                    $('#s_program').val(data.program.id).trigger('change');
                }
                if (data.benefactor) {
                    $('#s_benefactor').val(data.benefactor.id).trigger('change');
                }
                $('#s_address').val(data.address);
                $('#s_email').val(data.email);
                $('#s_sign_up').val(data.date_of_signup);
                $('#s_referral').val(data.referral.id).trigger('change');
                $('#s_gender').val(data.gender).trigger('change');
                $('#s_branch').val(data.branch.id).trigger('change');
                $('#s_course').val(data.course.id).trigger('change');
                $('#s_batch').val(data.batch).trigger('change');
                $('#s_year').val(data.departure_year.id).trigger('change');
                $('#s_remarks').val(data.remarks);
                $('#student_modal').modal('toggle');
                $('#student_modal').modal('show');
            }
        });
    });

    //Open TITP Student Modal (EDIT)
    $(document).on('click', '.edit_titp_student', function () {
        modal_close = false;
        $('#student_type_tab a[href="#titp_student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: { id: id },
            dataType: 'json',
            success: function success(data) {
                $('#t_add_edit').val('edit');
                $('#t_id').val(data.id);
                $('#t_fname').val(data.fname);
                $('#t_mname').val(data.mname);
                $('#t_lname').val(data.lname);
                $('#t_program').val(data.program.id).trigger('change');
                if (data.company) {
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

    //Open Intern Student Modal (EDIT)
    $(document).on('click', '.edit_intern_student', function () {
        modal_close = false;
        $('#student_type_tab a[href="#intern_student_form"]').tab('show');
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student',
            method: 'get',
            data: { id: id },
            dataType: 'json',
            success: function success(data) {
                $('#i_add_edit').val('edit');
                $('#i_id').val(data.id);
                $('#i_fname').val(data.fname);
                $('#i_mname').val(data.mname);
                $('#i_lname').val(data.lname);
                $('#i_branch').val(data.branch.id).trigger('change');
                $('#i_university').val(data.university.id).trigger('change');
                $('#i_benefactor').val(data.benefactor.id).trigger('change');
                $('#i_contact').val(data.contact);
                $('#i_gender').val(data.gender).trigger('change');
                $('#i_birthdate').val(data.birthdate);
                $('#i_civil').val(data.civil_status).trigger('change');
                $('#i_course').val(data.course.id).trigger('change');
                $('#i_email').val(data.email);
                $('#i_address').val(data.address);
                $('#i_referral').val(data.referral.id).trigger('change');
                $('#i_sign_up').val(data.date_of_signup);
                $('#i_medical').val(data.date_of_medical);
                $('#i_completion').val(data.date_of_completion);
                $('#i_year').val(data.departure_year.id).trigger('change');
                $('#i_month').val(data.departure_month.id).trigger('change');
                $('#i_remarks').val(data.remarks);
                $('#student_modal').modal('toggle');
                $('#student_modal').modal('show');
            }
        });
    });

    //Delete Student
    $(document).on('click', '.delete_student', function () {
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
            preConfirm: function preConfirm(password) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/confirm_user',
                    data: { password: password },
                    method: 'POST',
                    success: function success(data) {
                        if (data == 0) {
                            swal('Password Incorrect!', 'Please try again', 'error');
                            return;
                        } else {
                            swal({
                                title: 'Are you sure?',
                                text: 'You are about to delete a student. This may affect multiple rows',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then(function (result) {
                                if (result.value) {
                                    $.ajax({
                                        headers: {
                                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        url: '/delete_student',
                                        method: 'get',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This Student has been Deleted', 'success', 'glyphicon-ok');

                                            refresh();
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    //Final School
    $(document).on('click', '.final_student', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Go for Final School?',
            text: 'This Student will be in Final School',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/final_student',
                    method: 'get',
                    data: { id: id },
                    dataType: 'text',
                    success: function success(data) {
                        notif('Success!', 'This Student is now in Final School!', 'success', 'glyphicon-ok');
                        view_profile(data);

                        refresh();
                    }
                });
            }
        });
    });

    //Back Out
    $(document).on('click', '.backout_student', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Student will backout?',
            text: 'This Student will be transferred to list of backouts',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/backout_student',
                    method: 'get',
                    data: { id: id },
                    dataType: 'text',
                    success: function success(data) {
                        notif('This Student has backed out!', '', 'warning', 'glyphicon-warning-sign');
                        view_profile(data);

                        refresh();
                    }
                });
            }
        });
    });

    //Continue (From Back Out)
    $(document).on('click', '.continue_student', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Student will apply again?',
            text: 'This Student will be transferred to list of Active Students',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/continue_student',
                    method: 'get',
                    data: { id: id },
                    dataType: 'text',
                    success: function success(data) {
                        if (current_status == 'Final School') {
                            notif('Success!', 'This Student is out of Final School!', 'success', 'glyphicon-ok');
                        } else {
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
    $(document).on('click', '.approve_student', function () {
        var id = $(this).attr('id');

        title = 'Student COE Approved?';
        text = 'Confirm that this student\'s COE is approved?';

        if (current_tab == 'TITP') {
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
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/approve_student',
                    method: 'get',
                    data: { id: id },
                    dataType: 'text',
                    success: function success(data) {
                        notif('Congratulations!', 'Student COE Approved!', 'success', 'glyphicon-ok');
                        view_profile(data);

                        refresh();
                    }
                });
            }
        });
    });

    //Deny (Only in Result Monitoring)
    $(document).on('click', '.deny_student', function () {
        var id = $(this).attr('id');

        title = 'Student COE Denied?';
        text = 'Confirm that this student\'s COE is denied?';

        if (current_tab == 'TITP') {
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
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/deny_student',
                    method: 'get',
                    data: { id: id },
                    dataType: 'text',
                    success: function success(data) {
                        notif('Student COE Denied', '', 'warning', 'glyphicon-warning-sign');
                        view_profile(data);

                        refresh();
                    }
                });
            }
        });
    });

    //Cancel (Only in Result Monitoring)
    $(document).on('click', '.cancel_student', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Student will Cancel?',
            text: 'Confirm that this student decided to cancel?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: '/cancel_student',
                    method: 'get',
                    data: { id: id },
                    dataType: 'text',
                    success: function success(data) {
                        notif('Student Cancelled', '', 'warning', 'glyphicon-warning-sign');
                        view_profile(data);

                        refresh();
                    }
                });
            }
        });
    });

    $(document).on('click', '.view_profile', function () {
        var id = $(this).attr('id');

        view_profile(id);
    });

    //Student Information -- START
    $(document).on('click', '.info_student', function () {
        $('#student_info_modal').modal('show');
        var id = $(this).attr('id');
        $.ajax({
            url: '/get_student_info/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#student_info_modal .title_name').text(data.lname + ', ' + data.fname);
                $('.add_emergency, .add_educational, .add_employment_history').attr('id', data.id);
                refresh_student_info(data.id);
            }
        });
    });

    $(document).on('click', '.add_emergency', function () {
        var id = $(this).attr('id');

        $('#e_stud_id').val(id);
        $('#e_add_edit').val('add');
        $('#student_info_modal').modal('hide');
        setTimeout(function () {
            $('#emergency_modal').modal('show');
        }, 500);
    });

    $(document).on('click', '.add_employment_history', function () {
        var id = $(this).attr('id');

        $('#eh_stud_id').val(id);
        $('#eh_add_edit').val('add');
        $('#student_info_modal').modal('hide');
        setTimeout(function () {
            $('#emp_history_modal').modal('show');
        }, 500);
    });

    $(document).on('click', '.add_educational', function () {
        var id = $(this).attr('id');

        $('#eb_stud_id').val(id);
        $('#eb_add_edit').val('add');
        $('#student_info_modal').modal('hide');
        setTimeout(function () {
            $('#educational_background_modal').modal('show');
        }, 500);
    });

    $(document).on('click', '.create_soa', function () {
        //var id = $(this).attr('id');

        //$('#eb_stud_id').val(id);
        //$('#eb_add_edit').val('add');
        //$('#student_info_modal').modal('hide');
        //setTimeout(function(){$('#soa_modal').modal('show')}, 500);
        $('#soa_modal').modal('show');
    });

    $(document).on('click', '.edit_emergency', function () {
        var id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_student_emergency/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#e_id').val(data.id);
                $('#e_stud_id').val(data.stud_id);
                $('#e_add_edit').val('edit');
                $('#e_fname').val(data.fname);
                $('#e_mname').val(data.mname);
                $('#e_lname').val(data.lname);
                $('#e_relationship').val(data.relationship);
                $('#e_contact').val(data.contact);
                $('#e_address').val(data.address);
                $('#student_info_modal').modal('hide');
                setTimeout(function () {
                    $('#emergency_modal').modal('show');
                }, 500);
            }
        });
    });

    $(document).on('click', '.edit_emp_history', function () {
        var id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_student_emp_history/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#eh_id').val(data.id);
                $('#eh_stud_id').val(data.stud_id);
                $('#eh_add_edit').val('edit');
                $('#eh_company').val(data.name);
                $('#eh_position').val(data.position);
                $('#eh_started').val(data.start);
                $('#eh_finished').val(data.finished);
                $('#student_info_modal').modal('hide');
                setTimeout(function () {
                    $('#emp_history_modal').modal('show');
                }, 500);
            }
        });
    });

    $(document).on('click', '.edit_education', function () {
        var id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_student_education/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#eb_id').val(data.id);
                $('#eb_stud_id').val(data.stud_id);
                $('#eb_add_edit').val('edit');
                $('#eb_school').val(data.name);
                $('#eb_start').val(data.start);
                $('#eb_end').val(data.end);
                $('#eb_level').val(data.level);
                $('#eb_course').val(data.course);
                $('#student_info_modal').modal('hide');
                setTimeout(function () {
                    $('#educational_background_modal').modal('show');
                }, 500);
            }
        });
    });

    $(document).on('click', '.delete_emergency', function () {
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
            preConfirm: function preConfirm(password) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/confirm_user',
                    data: { password: password },
                    method: 'POST',
                    success: function success(data) {
                        if (data == 0) {
                            swal('Password Incorrect!', 'Please try again', 'error');
                            return;
                        } else {
                            swal({
                                title: 'Are you sure?',
                                text: 'You are about to delete an emergency contact.',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then(function (result) {
                                if (result.value) {
                                    $.ajax({
                                        headers: {
                                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        url: '/delete_student_emergency',
                                        method: 'get',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This Data has been Deleted', 'success', 'glyphicon-ok');

                                            refresh_student_info(data);
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete_emp_history', function () {
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
            preConfirm: function preConfirm(password) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/confirm_user',
                    data: { password: password },
                    method: 'POST',
                    success: function success(data) {
                        if (data == 0) {
                            swal('Password Incorrect!', 'Please try again', 'error');
                            return;
                        } else {
                            swal({
                                title: 'Are you sure?',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then(function (result) {
                                if (result.value) {
                                    $.ajax({
                                        headers: {
                                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        url: '/delete_student_emp_history',
                                        method: 'get',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This Data has been Deleted', 'success', 'glyphicon-ok');

                                            refresh_student_info(data);
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('click', '.delete_education', function () {
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
            preConfirm: function preConfirm(password) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/confirm_user',
                    data: { password: password },
                    method: 'POST',
                    success: function success(data) {
                        if (data == 0) {
                            swal('Password Incorrect!', 'Please try again', 'error');
                            return;
                        } else {
                            swal({
                                title: 'Are you sure?',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then(function (result) {
                                if (result.value) {
                                    $.ajax({
                                        headers: {
                                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        url: '/delete_student_education',
                                        method: 'get',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This Data has been Deleted', 'success', 'glyphicon-ok');

                                            refresh_student_info(data);
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('submit', '#emergency_form', function (e) {
        e.preventDefault();

        var input = $('.save_emergency');
        var button = document.getElementsByClassName("save_emergency")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_student_emergency',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                if ($('#emergency_continuous').is(':checked')) {
                    var id = $('#e_stud_id').val();
                    student_other_modal_clear();
                    $('#e_stud_id').val(id);
                } else {
                    $('#emergency_modal').modal('hide');
                }
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_student_info(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#emp_history_form', function (e) {
        e.preventDefault();

        var input = $('.save_employment_history');
        var button = document.getElementsByClassName("save_employment_history")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_student_emp_history',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                if ($('#emp_continuous').is(':checked')) {
                    var id = $('#eh_stud_id').val();
                    student_other_modal_clear();
                    $('#eh_stud_id').val(id);
                } else {
                    $('#emp_history_modal').modal('hide');
                }
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_student_info(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#educational_background_form', function (e) {
        e.preventDefault();

        var input = $('.save_educational_background');
        var button = document.getElementsByClassName("save_educational_background")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_student_education',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                if ($('#educ_continuous').is(':checked')) {
                    var id = $('#eb_stud_id').val();
                    student_other_modal_clear();
                    $('#eb_stud_id').val(id);
                } else {
                    $('#educational_background_modal').modal('hide');
                }
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_student_info(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Student Information -- END

    //Course Select 2
    $('#course, #l_course, #s_course, #t_course, #i_course').select2({
        placeholder: 'Select Course',
        ajax: {
            url: "/courseAll",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    //Program Select 2
    $('#program').select2({
        allowClear: true,
        placeholder: 'Select Program',
        ajax: {
            url: "/programAll",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    $('#s_program').select2({
        allowClear: true,
        placeholder: 'Select Program',
        ajax: {
            url: "/programSSW",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    $('#t_program').select2({
        allowClear: true,
        placeholder: 'Select Program',
        ajax: {
            url: "/programTITP",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    //School Select 2
    $('#school').select2({
        allowClear: true,
        placeholder: 'Select School',
        ajax: {
            url: "/schoolAll",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    //University Select 2
    $('#i_university').select2({
        allowClear: true,
        placeholder: 'Select University',
        ajax: {
            url: "/universityAll",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    //Benefactor Select 2
    $('#benefactor, #s_benefactor, #i_benefactor').select2({
        allowClear: true,
        placeholder: 'Select Benefactor',
        ajax: {
            url: "/benefactorAll",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    //Company Select 2
    $('#t_company').select2({
        allowClear: true,
        placeholder: 'Select Company',
        ajax: {
            url: "/companyAll",
            dataType: 'json',

            data: function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            },

            processResults: function processResults(data) {
                return {
                    results: data.results
                };
            }
        }
    });

    function view_profile(id) {
        $.ajax({
            url: '/view_profile_student/' + id,
            method: 'get',
            dataType: 'JSON',
            success: function success(data) {
                $('#p_picture').attr('src', './storage/img/student/' + data.picture);

                if (data.mname) {
                    $('#p_stud_name').text(data.lname + ', ' + data.fname + ' ' + data.mname);
                } else {
                    $('#p_stud_name').text(data.lname + ', ' + data.fname);
                }

                if (data.program) {
                    if (data.program.name != 'Language Only' && data.program.name != 'SSW (Careworker)' && data.program.name != 'SSW (Hospitality)') {
                        $('#p_departure').text(data.departure_year.name + ' ' + data.departure_month.name);
                    } else {
                        $('#p_departure').text('N/A');
                    }
                } else {
                    $('#p_departure').text(data.departure_year.name + ' ' + data.departure_month.name);
                }

                $('#p_contact').text(data.contact);
                $('#p_batch').text(data.batch ? data.batch : '-');
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

                var emergency = data.emergency ? data.emergency.length != 0 ? '' : '-' : '-';
                if (data.emergency) {
                    for (var x = 0; x < data.emergency.length; x++) {
                        var i = data.emergency[x];
                        emergency += i.fname + ' ' + i.lname + ' ' + i.mname + '<br>' + i.relationship + '<br>' + i.contact + '<br>' + i.address;
                        if (x != data.emergency.length - 1) {
                            emergency += '<br><br>';
                        }
                    }
                }
                $('#p_emergency').html(emergency);
            }
        });
    }

    $(document).on('click', '.print_student_profile', function () {
        if (!$(this).hasClass('disabled')) {
            var id = $(this).attr('id');

            window.open('/studentPDF/' + id, '_blank');
        }
    });

    //FUNCTIONS -- END
});

/***/ }),

/***/ 9:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/js/pages/students.js");


/***/ })

/******/ });