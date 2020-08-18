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
/******/ 	return __webpack_require__(__webpack_require__.s = 10);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/student_class.js":
/***/ (function(module, exports) {

$(document).ready(function () {

    //INITIALIZE -- START

    var dayCheck,
        completeCheck = false,
        sensei,
        class_select,
        student;
    var current_class_tab = 'Ongoing'; //Ongoing, Complete, all
    var current_class_select = 0;
    var current_tab = 'Students';

    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    $('.timepicker').timepicker({
        showSeconds: false,
        showMeridian: false,
        defaultTime: false,
        showInputs: false,
        pickTime: false,
        template: false
    });

    $('.select2').select2();

    $('#date_class').select2({
        placeholder: 'Select Class'
    });

    $('#student_class').select2({
        placeholder: 'Select Student'
    });

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger: 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $('input, select').attr('autocomplete', 'off');

    $("#add_class_modal").on("hidden.bs.modal", function (e) {
        $('#add_class_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find(".add_start_time, .add_end_time, #start_date, #end_date, #remarks, select").val('').end();
        $(this).find(".add_start_time, .add_end_time").prop('readonly', true);
        $('.addCheck').prop('checked', false);
        $('.select2').trigger('change.select2');
    });

    $("#assign_student_class_modal").on("hidden.bs.modal", function (e) {
        assign_student_modal_clear();
        $('#assign_continuous').bootstrapToggle('off');
    });

    function assign_student_modal_clear() {
        $('#assign_student_class_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#assign_student_class_modal').find(".select2, input").val('').end();
        $('#assign_student_class_modal').find("#date_class, #student_class").prop('disabled', true);
        $('.completeCheck').prop('checked', false);
        completeCheck = false;
        $('.select2').trigger('change.select2');
    }

    $('#assign_continuous').bootstrapToggle('off');

    function disableTabs() {
        $('li.tab_pick, .class_pick').addClass('disabled').css('cursor', 'not-allowed');

        $('a.tab_pick, .class_pick').addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);

        $('#edit_class_form').find(".edit_start_time, .edit_end_time, #e_start_date, #e_end_date, #e_remarks, select").val('').end();
        $('#edit_class_form').find(".edit_start_time, .edit_end_time").prop('readonly', true);
        $('.editCheck').prop('checked', false);
        $('.select2').trigger('change.select2');
    }

    function enableTabs() {
        if (current_class_select != 0) {
            $('li.tab_pick, .class_pick').removeClass('disabled').css('cursor', 'pointer');

            $('a.tab_pick, .class_pick').removeClass('disabled').css('pointer-events', 'auto');

            get_settings();
        } else {
            $('li.stud_pick, .class_pick').removeClass('disabled').css('cursor', 'pointer');

            $('a.stud_pick, .class_pick').removeClass('disabled').css('pointer-events', 'auto');
        }

        $('.refresh_table').attr('disabled', false);
    }

    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    //COLUMNS & COLUMNDEFS

    var columns_students_class = [
    //{data: 'complete', name: 'complete'},
    { data: 'name', name: 'name' }, { data: 'student.contact', name: 'contact' }, { data: 'student.program.name', name: 'program' }, { data: 'departure', name: 'departure' }, { data: 'student.status', name: 'status' }, { data: 'class_status', name: 'class_status' }, { data: 'start_date', name: 'start_date' }, { data: 'end_date', name: 'end_date' }, { data: "action", orderable: false, searchable: false }];

    var columns_with_class = [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'program.name', name: 'program' }, { data: 'departure', name: 'departure' }, { data: 'status', name: 'status' }, { data: 'class_status', name: 'class_status' }, { data: 'action', name: 'action' }];

    var columns_no_class = [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'program.name', name: 'program' }, { data: 'departure', name: 'departure' }, { data: 'status', name: 'status' }];

    var columns_all_class = [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'program.name', name: 'program' }, { data: 'departure', name: 'departure' }, { data: 'status', name: 'status' }, { data: 'class_status', name: 'class_status' }];

    var columns_student_class_history = [{ data: 'sensei', name: 'sensei' }, { data: 'start_date', name: 'start_date' }, { data: 'end_date', name: 'end_date' }, { data: 'current_class.remarks', name: 'remarks' }];

    function refresh_student_class_table() {
        $('#student_class_table').DataTable({
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
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/class_students',
                data: {
                    current_class_select: current_class_select
                }
            },
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_students_class
        });
    }

    function refresh_student_with_class_table() {
        $('#student_with_class_table').DataTable({
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
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/with_class_students'
            },
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_with_class
        });
    }

    function refresh_student_no_class_table() {
        $('#student_no_class_table').DataTable({
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
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/no_class_students'
            },
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_no_class
        });
    }

    function student_all_class_table() {
        $('#student_all_class_table').DataTable({
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
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/all_class_students'
            },
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_all_class
        });
    }

    function student_class_history_table(id) {
        $('#student_class_history_table').DataTable({
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
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_student_class_history/' + id
            },
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            columns: columns_student_class_history,
            order: [[2, 'desc']]
        });
    }

    //DATATABLES -- END

    //FUNCTIONS -- START

    $('.addCheck').change(function () {
        dayCheck = $(this).next().val();
        startTimeInput = document.getElementsByClassName('add_start_time')[dayCheck - 1];
        endTimeInput = document.getElementsByClassName('add_end_time')[dayCheck - 1];
        if ($(this).is(':checked')) {
            $([startTimeInput, endTimeInput]).prop('readonly', false);
        } else {
            $([startTimeInput, endTimeInput]).prop('readonly', true);
            $([startTimeInput, endTimeInput]).val('');
        }
    });

    $('.editCheck').change(function () {
        dayCheck = $(this).next().val();
        startTimeInput = document.getElementsByClassName('edit_start_time')[dayCheck - 1];
        endTimeInput = document.getElementsByClassName('edit_end_time')[dayCheck - 1];
        if ($(this).is(':checked')) {
            $([startTimeInput, endTimeInput]).prop('readonly', false);
        } else {
            $([startTimeInput, endTimeInput]).prop('readonly', true);
            $([startTimeInput, endTimeInput]).val('');
        }
    });

    //Open Add Class Modal
    $('.add_class').on('click', function () {
        $('#add_class_modal').modal('toggle');
        $('#add_class_modal').modal('show');
    });

    $(document).on('click', '.edit_student_date', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student_date/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#edit_student_class_id').val(data.id);
                $('#student_name_temp').val(data.student.lname + ', ' + data.student.fname);
                $('#e_student_start_date').val(data.start_date);
                $('#e_student_end_date').val(data.end_date);
                if (data.end_date) {
                    $('#e_student_end_date').attr('required', true);
                    $('#e_student_end_date').addClass('required');
                } else {
                    $('#e_student_end_date').attr('required', false);
                    $('#e_student_end_date').removeClass('required');
                }

                $('#edit_student_date_modal').modal('toggle');
                $('#edit_student_date_modal').modal('show');
            }
        });
    });

    $(document).on('submit', '#add_class_form', function (e) {
        e.preventDefault();

        var input = $('.save_class');
        var button = document.getElementsByClassName("save_class")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/add_class',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                $('#add_class_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#edit_class_form', function (e) {
        e.preventDefault();

        var input = $('.e_save_class');
        var button = document.getElementsByClassName("e_save_class")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/edit_class',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#edit_student_date_form', function (e) {
        e.preventDefault();

        var input = $('.save_edit_student_date');
        var button = document.getElementsByClassName("save_edit_student_date")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/edit_student_date',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                $('#edit_student_date_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Open Assign Student Class Modal
    $('.assign_student_class').on('click', function () {
        $('#assign_student_class_modal').modal('toggle');
        $('#assign_student_class_modal').modal('show');
    });

    function load_classes() {
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_class/' + current_class_tab,
            method: 'get',
            dataType: 'json',
            success: function success(data) {

                $('#class_box').empty();

                var html = '';

                html += '<ul class="list-group list-group-unbordered">';

                for (var x = 0; x < data.class_settings.length; x++) {
                    console.log(data.class_settings[x]);
                    var counter = true;
                    var days = '';
                    for (var y = 0; y < 6; y++) {
                        if (data.class_settings[x].class_day[y].start_time) {
                            if (counter == false) {
                                days += ' • ';
                            }
                            days += '<span data-container="body" data-toggle="tooltip" \n                            data-placement="top"\n                            title="' + data.class_settings[x].class_day[y].start_time + ' ~ ' + (data.class_settings[x].class_day[y].end_time ? data.class_settings[x].class_day[y].end_time : 'TBD') + '">' + data.class_settings[x].class_day[y].day_name.abbrev + '</span>';

                            counter = false;
                        }
                    }
                    html += '\n                    <li class="list-group-item class_pick" id="' + data.class_settings[x].id + '">\n                        <p class="class_get_id" style="word-wrap: break-word;">' + 'Class ID: ' + data.class_settings[x].id + ' | ' + data.class_settings[x].start_date + ' ~ ' + (data.class_settings[x].end_date ? data.class_settings[x].end_date : 'TBD') + '<br>' + '<b>' + data.class_settings[x].sensei.fname + ' ' + data.class_settings[x].sensei.lname + '</b><br>' + '<span style="cursor:help;">' + days + '<br>' + '<span class="label label-success" data-container="body" data-toggle="tooltip" data-placement="top" title="Complete">' + data.class_settings[x].complete + '</span>&nbsp;' + '<span class="label label-danger" data-container="body" data-toggle="tooltip" data-placement="top" title="Back Out">' + data.class_settings[x].backout + '</span>&nbsp;' + '<span class="label label-info" data-container="body" data-toggle="tooltip" data-placement="top" title="Active">' + data.class_settings[x].active + '</span>&nbsp;' + '<span class="label label-warning" data-container="body" data-toggle="tooltip" data-placement="top" title="No. of Students">' + data.class_settings[x].all + '</span><br>' + '<span> Remarks: ' + (data.class_settings[x].remarks ? data.class_settings[x].remarks : '') + '</span>' + '</p>\n                    </li>';
                }

                html += '</ul>';

                $('#on_going_class_box span').text(data.on_going);
                $('#complete_class_box span').text(data.completed);
                $('#all_class_box span').text(data.all);
                $('#class_box').append(html);

                if (current_class_select != 0) {
                    $('.class_pick' + '#' + current_class_select).css('background-color', '#FEFAD4');
                }
            }
        });
    }

    function get_settings() {
        $.ajax({
            url: '/get_class_settings/' + current_class_select,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#e_sensei').val(data.sensei_id).trigger('change');
                $('#e_start_date').val(data.start_date);
                $('#e_end_date').val(data.end_date);
                $('#edit_class_id').val(data.id);

                for (var x = 0; x < 6; x++) {
                    if (data.class_day[x].start_time) {
                        $($('.editCheck')[x]).prop('checked', true);
                        $($('.edit_start_time')[x]).prop('readonly', false);
                        $($('.edit_end_time')[x]).prop('readonly', false);
                        $($('.edit_start_time')[x]).val(data.class_day[x].start_time);
                        if (data.class_day[x].end_time) {
                            $($('.edit_end_time')[x]).val(data.class_day[x].end_time);
                        }
                    }
                }

                if (!$('#e_end_date').val()) {
                    $('.end_class').prop('disabled', true);
                } else {
                    $('.end_class').prop('disabled', false);
                }

                $('#e_remarks').val(data.remarks);
            }
        });
    }

    $('.completeCheck').change(function () {
        $('#sensei_class, #date_class, #current_student_class, #class_students_id, #current_end_date').val('').trigger('change');
        $("#date_class, #student_class, #current_end_date").prop('disabled', true);

        if ($(this).is(':checked')) {
            completeCheck = true;
            getSenseiClass();
        } else {
            completeCheck = false;
            getSenseiClass();
        }
    });

    $('#sensei_class').change(function () {
        $('#date_class, #student_class, #current_student_class, #class_students_id, #current_end_date').val('').trigger('change');
        $("#date_class").prop('disabled', false);
        $("#student_class, #current_end_date").prop('disabled', true);
        sensei = $(this).val();

        getDateClass();
    });

    $('#date_class').change(function () {
        class_select = $(this).val();
        if ($(this).val()) {
            $('#student_class, #current_student_class, #class_students_id, #current_end_date').val('').trigger('change');
            $('#student_class').val('').prop('disabled', false);
            $("#current_end_date").prop('disabled', true);
        }

        getStudentClass();
    });

    $('#student_class').change(function () {
        if ($(this).val()) {
            student = $(this).val();

            $.ajax({
                url: '/check_student_class/' + student,
                method: 'get',
                dataType: 'json',
                success: function success(data) {
                    if (data != false) {
                        $('#current_student_class').val(data.current_class.start_date + ' ~ ' + (data.current_class.end_date ? data.current_class.end_date : 'TBD') + ' (' + data.current_class.sensei.fname + ' ' + data.current_class.sensei.lname + ')');
                        $('#current_end_date').val('').prop('disabled', false);
                        $('#class_students_id').val(data.id);
                    } else {
                        $('#current_student_class').val('No Current Class');
                        $('#current_end_date').val('').prop('disabled', true);
                        $('#class_students_id').val('');
                    }
                }
            });
        }
    });

    $(document).on('submit', '#assign_student_class_form', function (e) {
        e.preventDefault();

        var input = $('.save_assign');
        var button = document.getElementsByClassName("save_assign")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/assign_student_class',
            data: $(this).serialize(),
            dataType: 'text',
            success: function success(data) {
                if (data == 'assigned') {
                    swal("Error!", "Student already assigned to this class.", "error");
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }

                $('#assign_continuous').is(':checked') ? assign_student_modal_clear() : $('#assign_student_class_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                refresh();
                button.disabled = false;
                input.html('SAVE CHANGES');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.delete_class', function () {
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
                                title: 'Warning',
                                text: 'Deleting this class will clear all data for this class.',
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
                                        url: '/delete_class',
                                        data: {
                                            current_class_select: current_class_select,
                                            password: password
                                        },
                                        method: 'get',
                                        type: 'json',
                                        success: function success(data) {
                                            $('.tab_pick a:first').tab('show');
                                            current_tab = 'Students';
                                            current_class_select = 0;
                                            notif('Success!', 'This class has been Deleted', 'success', 'glyphicon-ok');
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

    $(document).on('click', '.end_class', function () {
        swal({
            title: 'End Class?',
            text: 'This will put an End Date on all students under this class.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/end_class',
                    data: {
                        current_class_select: current_class_select
                    },
                    method: 'get',
                    type: 'json',
                    success: function success(data) {
                        notif('Success!', 'This class has ended', 'success', 'glyphicon-ok');
                        refresh();
                    }
                });
            }
        });
    });

    $(document).on('click', '.remove_student_class', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to remove a student from this class.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/remove_student_class/' + id,
                    type: 'json',
                    method: 'get',
                    success: function success(data) {
                        notif('Success!', 'This Student has been removed from this class', 'success', 'glyphicon-ok');
                        refresh();
                    }
                });
            }
        });
    });

    $(document).on('click', '.view_class_history', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/student_class_name/' + id,
            method: 'get',
            type: 'json',
            success: function success(data) {
                $('#student_class_history_modal').modal('toggle');
                $('#student_class_history_modal').modal('show');
                $('#student_class_name').html(data);

                student_class_history_table(id);
            }
        });
    });

    function getSenseiClass() {
        $('#sensei_class').select2({
            placeholder: 'Select Sensei',
            ajax: {
                url: '/senseiClass/' + completeCheck,
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
    }

    function getDateClass() {
        $('#date_class').select2({
            placeholder: 'Select Class',
            ajax: {
                url: '/dateClass',
                dataType: 'json',

                data: function data(params) {
                    return {
                        completeCheck: completeCheck,
                        sensei: sensei,
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
    }

    function getStudentClass() {
        $('#student_class').select2({
            placeholder: 'Select Student',
            ajax: {
                url: '/studentClass',
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
    }

    getSenseiClass();

    //CLASS BOX -- START

    $(document).on('click', '.class_pick', function () {
        if (current_class_select) {
            $('.class_pick' + '#' + current_class_select).css('background-color', '');
        }

        $(this).css('background-color', '#FEFAD4');
        current_class_select = $(this).attr('id');

        refresh();
    });

    $(document).on('click', '.class_nav_box', function () {
        $('.class_nav_box').attr('disabled', false);
        $(this).attr('disabled', true);
        current_class_tab = $(this).find('b').text();
        load_classes();
    });

    //CLASS BOX -- END

    function refresh() {
        disableTabs();
        load_classes();
        switch (current_tab) {
            case 'Students':
                refresh_student_class_table();
                break;
            case 'With Classes':
                refresh_student_with_class_table();
                break;
            case 'No Classes':
                refresh_student_no_class_table();
                break;
            case 'All':
                student_all_class_table();
                break;
            default:
                enableTabs();
                break;
        }
    }

    $(document).on('click', '.refresh_table', function () {
        refresh();
    });

    refresh();

    $(document).on('click', '.tab_pick', function () {
        if (!$(this).hasClass('disabled')) {
            current_tab = $(this).text();
            refresh();
        }
    });

    //FUNCTIONS -- END
});

/***/ }),

/***/ 10:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/js/pages/student_class.js");


/***/ })

/******/ });