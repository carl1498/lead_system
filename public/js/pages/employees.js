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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/employees.js":
/***/ (function(module, exports) {

$(document).ready(function () {

    //INITIALIZE -- START

    var current_branch = 'All';
    var employee_status = $('#status_select').val();
    var modal_close = true;

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

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger: 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $('.select2').select2();

    $('#employee_modal').on('shown.bs.modal', function () {
        $('#fname').focus();
    });

    $("#employee_modal").on("hidden.bs.modal", function (e) {
        $('#employee_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
        modal_close = true;
    });

    $("#account_modal").on("hidden.bs.modal", function (e) {
        $(this).find("input,textarea,select").val('').end();
        modal_close = true;
    });

    $("#emergency_modal, #spouse_modal, #child_modal").on("hidden.bs.modal", function (e) {
        $(this).find("input,textarea,select").val('').end();
        setTimeout(function () {
            $('#employee_family_modal').modal('show');
        }, 500);
    });

    $("#prev_employment_modal, #educational_background_modal, #resign_modal, #rehire_modal").on("hidden.bs.modal", function (e) {
        $(this).find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
        setTimeout(function () {
            $('#employee_history_modal').modal('show');
        }, 500);
    });

    $("#employee_history_modal, #employee_family_modal").on("hidden.bs.modal", function (e) {
        modal_close = true;
    });

    $("#resign_modal, #rehire_modal").on("hidden.bs.modal", function (e) {
        $(this).find("#resignation_date, #rehiring_date").val('').end();
        modal_close = true;
    });

    $('input, select').attr('autocomplete', 'off');

    function disableTabs() {
        $('li.tab_pick').addClass('disabled').css('cursor', 'not-allowed');
        $('a.tab_pick').addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs() {
        $('li.tab_pick').removeClass('disabled').css('cursor', 'pointer');
        $('a.tab_pick').removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh() {
        disableTabs();
        update_buttons();

        if (current_branch == 'All') {
            refresh_employee_all();
        } else {
            refresh_employee_branch();
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

    var columns_employees = [{ data: 'name', name: 'name' }, { data: 'contact_personal', name: 'contact_personal' }, { data: 'contact_business', name: 'contact_business' }, { data: 'birthdate', name: 'birthdate' }, { data: 'gender', name: 'gender' }, { data: 'email', name: 'email' }, { data: 'role.name', name: 'role.name' }, { data: 'hired_date', name: 'hired_date' }, { data: 'employment_status', name: 'status' }, { data: "action", orderable: false, searchable: false }];

    var columns_employees_all = [{ data: 'name', name: 'name' }, { data: 'branch.name', name: 'branch' }, { data: 'contact_personal', name: 'contact_personal' }, { data: 'contact_business', name: 'contact_business' }, { data: 'birthdate', name: 'birthdate' }, { data: 'gender', name: 'gender' }, { data: 'email', name: 'email' }, { data: 'role.name', name: 'role.name' }, { data: 'hired_date', name: 'hired_date' }, { data: 'employment_status', name: 'status' }, { data: "action", orderable: false, searchable: false }];

    function update_buttons() {
        buttons_format = [{ extend: 'excelHtml5', title: 'Employee - Branch(' + current_branch + ') Status(' + employee_status + ')',
            exportOptions: {
                columns: ':visible'
            } }, 'colvis'];
    }

    function refresh_employee_branch() {
        $('#employees_branch').DataTable({
            initComplete: function initComplete(settings, json) {
                enableTabs();
            },
            dom: 'Bflrtip',
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: buttons_format,
            responsive: true,
            ajax: {
                url: '/employee_branch',
                data: {
                    current_branch: current_branch,
                    employee_status: employee_status
                }
            },
            columns: columns_employees,
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });
    }

    function refresh_employee_all() {
        $('#employees_all').DataTable({
            initComplete: function initComplete(settings, json) {
                enableTabs();
            },
            dom: 'Bflrtip',
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            buttons: buttons_format,
            responsive: true,
            ajax: '/employee_all/' + employee_status,
            columns: columns_employees_all,
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });
    }

    refresh();

    function refresh_employment_history(id) {
        var employment_history_table = $('#employment_history_table').DataTable({
            paging: false,
            info: false,
            searching: false,
            destroy: true,
            ajax: '/view_employment_history/' + id,
            columns: [{ data: 'hired_date', name: 'hired_date' }, { data: 'until', name: 'until' }, { data: 'months', name: 'months' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });

        var prev_employment_history_table = $('#prev_employment_history_table').DataTable({
            paging: false,
            info: false,
            searching: false,
            destroy: true,
            responsive: true,
            scrollX: true,
            scrollCollapse: true,
            ajax: '/view_prev_employment_history/' + id,
            columns: [{ data: 'company', name: 'company' }, { data: 'address', name: 'address' }, { data: 'hired_date', name: 'hired_date' }, { data: 'until', name: 'until' }, { data: 'months', name: 'months' }, { data: 'salary', name: 'salary' }, { data: 'designation', name: 'designation' }, { data: 'employment_type', name: 'employment_type' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });

        var educational_background_table = $('#educational_background_table').DataTable({
            paging: false,
            info: false,
            searching: false,
            destroy: true,
            responsive: true,
            scrollX: true,
            scrollCollapse: true,
            ajax: '/view_educational_background/' + id,
            columns: [{ data: 'school', name: 'school' }, { data: 'start', name: 'start' }, { data: 'end', name: 'end' }, { data: 'course.name', name: 'course' }, { data: 'level', name: 'level' }, { data: 'awards', name: 'awards' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });
    }

    function refresh_employee_family(id) {
        var employee_emergency_table = $('#employee_emergency_table').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            destroy: true,
            ajax: '/view_employee_emergency/' + id,
            columns: [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'relationship', name: 'relationship' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });

        var employee_spouse_table = $('#employee_spouse_table').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            destroy: true,
            ajax: '/view_employee_spouse/' + id,
            columns: [{ data: 'name', name: 'name' }, { data: 'contact', name: 'contact' }, { data: 'birthdate', name: 'birthdate' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });

        var employee_child_table = $('#employee_child_table').DataTable({
            paging: false,
            ordering: false,
            info: false,
            searching: false,
            destroy: true,
            ajax: '/view_employee_child/' + id,
            columns: [{ data: 'name', name: 'name' }, { data: 'gender', name: 'gender' }, { data: 'birthdate', name: 'birthdate' }, { data: "action", orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });
    }

    //DATATABLES -- END

    //FUNCTIONS -- START

    $(document).on('change', '#status_select', function () {
        employee_status = $(this).val();
        refresh();
    });

    $('.tab_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_branch = $(this).text();
        }
    });

    //Add or Edit School
    $(document).on('submit', '#employee_form', function (e) {
        e.preventDefault();

        var input = $('.save_employee');
        var button = document.getElementsByClassName("save_employee")[0];

        button.disabled = true;
        input.html('SAVING...');

        var formData = new FormData($(this)[0]);

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_employee',
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
                $('#employee_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
                view_profile(data);
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });

        return false;
    });

    //Open Employee Modal (ADD)
    $('.add_employee').on('click', function () {
        modal_close = false;
        $('#add_edit').val('add');
        $('#employee_modal').modal('show');
    });

    //Open Employee Modal (EDIT)
    $(document).on('click', '.edit_employee', function () {
        modal_close = false;
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employee/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#add_edit').val('edit');
                $('#id').val(data.employee.id);
                $('#fname').val(data.employee.fname);
                $('#mname').val(data.employee.mname);
                $('#lname').val(data.employee.lname);
                $('#birthdate').val(data.employee.birthdate);
                $('#gender').val(data.employee.gender).trigger('change');
                $('#personal_no').val(data.employee.contact_personal);
                $('#business_no').val(data.employee.contact_business);
                $('#email').val(data.employee.email);
                $('#address').val(data.employee.address);
                $('#branch').val(data.employee.branch_id).trigger('change');
                $('#role').val(data.employee.role_id).trigger('change');
                $('#salary').val(data.employee.salary);
                $('#hired').val(data.employment_history.hired_date);
                $('#sss').val(data.benefits[0].id_number);
                $('#pagibig').val(data.benefits[1].id_number);
                $('#philhealth').val(data.benefits[2].id_number);
                $('#tin').val(data.benefits[3].id_number);
                $('#employee_modal').modal('show');
            }
        });
    });

    //Delete Employee
    $(document).on('click', '.delete_employee', function () {
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
                                text: 'You are about to delete an employee. This may affect multiple rows',
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
                                        url: '/delete_employee',
                                        method: 'get',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Deleted!', 'This Employee has been Deleted', 'success', 'glyphicon-ok');
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

    //ACCOUNTS -- START

    $(document).on('click', '.edit_account', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_account/' + id,
            dataType: 'json',
            success: function success(data) {
                $('#a_id').val(data.id);
                if (data.employee.mname) {
                    $('#emp_name').val(data.employee.lname + ', ' + data.employee.fname + ' ' + data.employee.mname);
                } else {
                    $('#emp_name').val(data.employee.lname + ', ' + data.employee.fname);
                }
                $('#username').val(data.username);

                $('#account_modal').modal('show');
            }
        });
    });

    $(document).on('submit', '#account_form', function (e) {
        e.preventDefault();

        if ($('#password').val() != '') {
            if ($('#password').val() != $('#confirm_password').val()) {
                swal('Error!', 'Password and Confirm Password must be identical', 'error');
                return;
            }
        }

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
                            $.ajax({
                                headers: {
                                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/save_account',
                                data: $('#account_form').serialize() + '&confirm_password=' + password,
                                method: 'POST',
                                dataType: 'text',
                                success: function success(data) {
                                    notif('Success!', 'User Data has been saved!', 'success', 'glyphicon-ok');
                                    $('#account_modal').modal('hide');
                                    refresh();
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    //ACCOUNTS -- END

    //RESIGN -- START

    $(document).on('click', '.resign_employee', function () {
        var id = $(this).attr('id');

        $('#r_id').val(id);
        $('#employee_history_modal').modal('hide');
        setTimeout(function () {
            $('#resign_modal').modal('show');
        }, 500);
    });

    $(document).on('submit', '#resign_form', function (e) {
        e.preventDefault();

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
                            $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/save_resign_employee',
                                method: 'POST',
                                dataType: 'text',
                                data: $('#resign_form').serialize() + '&password=' + password,
                                success: function success(data) {
                                    $('#resign_modal').modal('hide');
                                    notif('Employee now resigned.', '', 'info', 'glyphicon-info-sign');

                                    $('.title_status').text('Resigned');
                                    $('.resign_rehire').attr('data-original-title', 'Rehire').attr('id', data).removeClass('resign_employee').addClass('rehire_employee');
                                    $('.resign_rehire i').removeClass('fa-sign-out-alt').addClass('fa-sign-in-alt');

                                    refresh_employment_history(data);
                                    refresh();
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    //RESIGN -- END

    //REHIRE -- START

    $(document).on('click', '.rehire_employee', function () {
        var id = $(this).attr('id');

        $('#rh_id').val(id);
        $('#employee_history_modal').modal('hide');
        setTimeout(function () {
            $('#rehire_modal').modal('show');
        }, 500);
    });

    $(document).on('submit', '#rehire_form', function (e) {
        e.preventDefault();

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
                            $.ajax({
                                url: '/save_rehire_employee',
                                method: 'POST',
                                dataType: 'text',
                                data: $('#rehire_form').serialize() + '&password=' + password,
                                success: function success(data) {
                                    $('#rehire_modal').modal('hide');
                                    notif('Success!', 'Employee now rehired', 'success', 'glyphicon-ok');

                                    $('.title_status').text('Active');
                                    $('.resign_rehire').attr('data-original-title', 'Resign').attr('id', data).removeClass('rehire_employee').addClass('resign_employee');
                                    $('.resign_rehire i').removeClass('fa-sign-in-alt').addClass('fa-sign-out-alt');

                                    refresh_employment_history(data);
                                    refresh();
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    //REHIRE -- END

    //VIEW PROFILE -- START

    $(document).on('click', '.view_employee_profile', function () {
        var id = $(this).attr('id');

        view_profile(id);
    });

    //VIEW PROFILE -- END

    //EMPLOYEE HISTORY -- START

    $(document).on('click', '.history_employee', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employee/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#employee_history_modal .title_name').text(data.employee.fname + ' ' + data.employee.lname);
                $('#employee_history_modal .title_status').text(data.employee.employment_status);
                $('#employee_history_modal .title_probationary').text(data.employee.probationary + ' - Lead Employment History');

                if (data.employee.employment_status == 'Active') {
                    $('.resign_rehire').attr({ 'data-original-title': 'Resign', 'id': id }).removeClass('rehire_employee').addClass('resign_employee');
                    $('.resign_rehire i').removeClass('fa-sign-in-alt').addClass('fa-sign-out-alt');
                } else {
                    $('.resign_rehire').attr({ 'data-original-title': 'Rehire', 'id': id }).removeClass('resign_employee').addClass('rehire_employee');
                    $('.resign_rehire i').removeClass('fa-sign-out-alt').addClass('fa-sign-in-alt');
                }

                $('.add_employment_history, .add_educational').attr({ 'id': id });

                refresh_employment_history(id);
            }
        });

        $('#employee_history_modal').modal('show');
    });

    $(document).on('click', '.edit_employment_history', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employment_history/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#eh_id').val(data.id);
                $('#edit_hired_date').val(data.hired_date);
                $('#edit_until').val(data.until);
            }
        });

        $('#edit_employee_history_modal').modal('show');
    });

    $(document).on('submit', '#edit_employee_history_form', function (e) {
        e.preventDefault();

        $.ajax({
            url: '/save_employment_history',
            method: 'POST',
            dataType: 'text',
            data: $(this).serialize(),
            success: function success(data) {
                notif('Edit Success!', '', 'success', 'glyphicon-ok');

                $('#edit_employee_history_modal').modal('hide');
                refresh_employment_history(data);
                view_profile(data);
                refresh();
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.edit_employment_history', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employment_history/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#eh_id').val(data.id);
                $('#edit_hired_date').val(data.hired_date);
                $('#edit_until').val(data.until);
            }
        });

        $('#edit_employee_history_modal').modal('show');
    });

    //Employee Background

    $(document).on('click', '.add_employment_history', function () {
        var id = $(this).attr('id');

        $('#pe_emp_id').val(id);
        $('#pe_add_edit').val('add');
        $('#employee_history_modal').modal('hide');
        setTimeout(function () {
            $('#prev_employment_modal').modal('show');
        }, 500);
    });

    $(document).on('submit', '#prev_employment_form', function (e) {
        e.preventDefault();

        var input = $('.save_prev_employment');
        var button = document.getElementsByClassName("save_prev_employment")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_prev_employment_history',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                $('#prev_employment_modal').modal('hide');
                setTimeout(function () {
                    $('#employee_history_modal').modal('show');
                }, 500);
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_employment_history(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.edit_prev_employment_history', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_prev_employment_history/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#pe_add_edit').val('edit');
                $('#pe_id').val(data.id);
                $('#pe_emp_id').val(data.emp_id);
                $('#pe_company').val(data.company);
                $('#pe_address').val(data.address);
                $('#pe_hired_date').val(data.hired_date);
                $('#pe_until').val(data.until);
                $('#pe_salary').val(data.salary);
                $('#pe_designation').val(data.designation);
                $('#pe_employment_type').val(data.employment_type);
                $('#employee_history_modal').modal('hide');
                setTimeout(function () {
                    $('#prev_employment_modal').modal('show');
                }, 500);
            }
        });
    });

    $(document).on('click', '.delete_prev_employment_history', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete an employee employment history',
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
                    url: '/delete_prev_employment_history/' + id,
                    method: 'get',
                    type: 'json',
                    success: function success(data) {
                        refresh_employment_history(data);
                        view_profile(data);
                        notif('Deleted!', 'This Employment History has been Deleted', 'success', 'glyphicon-ok');
                    }
                });
            }
        });
    });

    //Educational Background

    $(document).on('click', '.add_educational', function () {
        var id = $(this).attr('id');

        $('#eb_emp_id').val(id);
        $('#eb_add_edit').val('add');
        $('#employee_history_modal').modal('hide');
        setTimeout(function () {
            $('#educational_background_modal').modal('show');
        }, 500);
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
            url: '/save_educational_background',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                $('#educational_background_modal').modal('hide');
                setTimeout(function () {
                    $('#employee_history_modal').modal('show');
                }, 500);
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_employment_history(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.edit_educational_background', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_educational_background/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#eb_add_edit').val('edit');
                $('#eb_id').val(data.id);
                $('#eb_emp_id').val(data.emp_id);
                $('#eb_school').val(data.school);
                $('#eb_start').val(data.start);
                $('#eb_end').val(data.end);
                $('#eb_course').val(data.course_id).trigger('change');
                $('#eb_level').val(data.level);
                $('#eb_awards').val(data.awards);
                $('#employee_history_modal').modal('hide');
                setTimeout(function () {
                    $('#educational_background_modal').modal('show');
                }, 500);
            }
        });
    });

    $(document).on('click', '.delete_educational_background', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete an employee educational background',
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
                    url: '/delete_educational_background/' + id,
                    method: 'get',
                    type: 'json',
                    success: function success(data) {
                        refresh_employment_history(data);
                        view_profile(data);
                        notif('Deleted!', 'This Educational Background has been Deleted', 'success', 'glyphicon-ok');
                    }
                });
            }
        });
    });

    //EDUCATIONAL BACKGROUND -- END

    //EMPLOYEE FAMILY -- START

    $(document).on('click', '.family_employee', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employee/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('.add_emergency').attr('id', data.employee.id);
                $('.add_spouse').attr('id', data.employee.id);
                $('.add_child').attr('id', data.employee.id);
                $('#employee_family_modal .modal-title').text(data.employee.fname + ' ' + data.employee.lname);
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });

        refresh_employee_family(id);

        $('#employee_family_modal').modal('show');
    });

    //Employee Emergency Number

    $(document).on('click', '.add_emergency', function () {
        var id = $(this).attr('id');

        $('#e_emp_id').val(id);
        $('#e_add_edit').val('add');
        $('#employee_family_modal').modal('hide');
        setTimeout(function () {
            $('#emergency_modal').modal('show');
        }, 500);
    });

    $(document).on('click', '.edit_employee_emergency', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employee_emergency/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#e_add_edit').val('edit');
                $('#e_id').val(data.id);
                $('#e_emp_id').val(data.emp_id);
                $('#e_fname').val(data.fname);
                $('#e_mname').val(data.mname);
                $('#e_lname').val(data.lname);
                $('#e_relationship').val(data.relationship);
                $('#e_contact').val(data.contact);
                $('#employee_family_modal').modal('hide');
                setTimeout(function () {
                    $('#emergency_modal').modal('show');
                }, 500);
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
            url: '/save_employee_emergency',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                $('#emergency_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_employee_family(data);
                view_profile(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.delete_employee_emergency', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete an employee emergency number',
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
                    url: '/delete_employee_emergency/' + id,
                    method: 'get',
                    type: 'json',
                    success: function success(data) {
                        refresh_employee_family(data);
                        view_profile(data);
                        notif('Deleted!', 'This Emergency Number has been Deleted', 'success', 'glyphicon-ok');
                    }
                });
            }
        });
    });

    //Employee Spouse

    $(document).on('click', '.add_spouse', function () {
        var id = $(this).attr('id');

        $('#s_emp_id').val(id);
        $('#s_add_edit').val('add');
        $('#employee_family_modal').modal('hide');
        setTimeout(function () {
            $('#spouse_modal').modal('show');
        }, 500);
    });

    $(document).on('click', '.edit_employee_spouse', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employee_spouse/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#s_add_edit').val('edit');
                $('#s_id').val(data.id);
                $('#s_emp_id').val(data.emp_id);
                $('#s_fname').val(data.fname);
                $('#s_mname').val(data.mname);
                $('#s_lname').val(data.lname);
                $('#s_birthdate').val(data.birthdate);
                $('#s_contact').val(data.contact);
                $('#employee_family_modal').modal('hide');
                setTimeout(function () {
                    $('#spouse_modal').modal('show');
                }, 500);
            }
        });
    });

    $(document).on('submit', '#spouse_form', function (e) {
        e.preventDefault();

        var input = $('.save_spouse');
        var button = document.getElementsByClassName("save_spouse")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_employee_spouse',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                $('#spouse_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_employee_family(data);
                view_profile(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.delete_employee_spouse', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete an employee spouse',
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
                    url: '/delete_employee_spouse/' + id,
                    method: 'get',
                    type: 'json',
                    success: function success(data) {
                        refresh_employee_family(data);
                        view_profile(data);
                        notif('Deleted!', 'Spouse has been Deleted', 'success', 'glyphicon-ok');
                    }
                });
            }
        });
    });

    //Employee Child

    $(document).on('click', '.add_child', function () {
        var id = $(this).attr('id');

        $('#c_emp_id').val(id);
        $('#c_add_edit').val('add');
        $('#employee_family_modal').modal('hide');
        setTimeout(function () {
            $('#child_modal').modal('show');
        }, 500);
    });

    $(document).on('click', '.edit_employee_child', function () {
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employee_child/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#c_add_edit').val('edit');
                $('#c_id').val(data.id);
                $('#c_emp_id').val(data.emp_id);
                $('#c_fname').val(data.fname);
                $('#c_mname').val(data.mname);
                $('#c_lname').val(data.lname);
                $('#c_birthdate').val(data.birthdate);
                $('#c_gender').val(data.gender).trigger('change');
                $('#employee_family_modal').modal('hide');
                setTimeout(function () {
                    $('#child_modal').modal('show');
                }, 500);
            }
        });
    });

    $(document).on('submit', '#child_form', function (e) {
        e.preventDefault();

        var input = $('.save_child');
        var button = document.getElementsByClassName("save_child")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_employee_child',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                $('#child_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_employee_family(data);
                view_profile(data);
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.delete_employee_child', function () {
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete an employee child',
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
                    url: '/delete_employee_child/' + id,
                    method: 'get',
                    type: 'json',
                    success: function success(data) {
                        refresh_employee_family(data);
                        view_profile(data);
                        notif('Deleted!', 'Child has been Deleted', 'success', 'glyphicon-ok');
                    }
                });
            }
        });
    });

    //EMPLOYEE FAMILY -- END

    function view_profile(id) {
        $.ajax({
            url: '/view_profile_employee/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#p_picture').attr('src', './storage/img/employee/' + data.picture);
                if (data.mname) {
                    $('#p_emp_name').text(data.lname + ', ' + data.fname + ' ' + data.mname);
                } else {
                    $('#p_emp_name').text(data.lname + ', ' + data.fname);
                }

                $('#p_position').text(data.role.name);

                $('#p_business').text(data.contact_business ? data.contact_business : '-');
                $('#p_personal').text(data.contact_personal ? data.contact_personal : '-');
                $('#p_email').text(data.email);
                $('#p_birthdate').text(data.birthdate + ' (' + data.age + ')');
                $('#p_gender').text(data.gender);
                $('#p_branch').text(data.branch.name);
                $('#p_status').text(data.employment_status + ' ' + data.leaves);
                $('#p_probationary').text(data.probationary);
                $('#p_hired').text(data.current_employment_status.hired_date ? data.current_employment_status.hired_date : '-');
                var months = data.months ? data.months : '';
                $('#p_until').text(data.current_employment_status.until ? data.current_employment_status.until + ' (' + months + ')' : 'Present (' + data.months + ')');
                $('#p_sss').text(data.benefits[0].id_number ? data.benefits[0].id_number : '-');
                $('#p_pagibig').text(data.benefits[1].id_number ? data.benefits[1].id_number : '-');
                $('#p_philhealth').text(data.benefits[2].id_number ? data.benefits[2].id_number : '-');
                $('#p_tin').text(data.benefits[3].id_number ? data.benefits[3].id_number : '-');
                var html = data.employee_emergency.length != 0 ? '' : '-';
                for (var x = 0; x < data.employee_emergency.length; x++) {
                    i = data.employee_emergency[x];
                    html += i.fname + ' ' + i.lname + '<br>' + i.relationship + '<br>' + i.contact;
                    if (x != data.employee_emergency.length - 1) {
                        html += '<br><br>';
                    }
                }
                $('#p_emergency').html(html);
            }
        });
    }

    //Course Select 2
    $('#eb_course').select2({
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

    //FUNCTIONS -- END
});

/***/ }),

/***/ 3:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/js/pages/employees.js");


/***/ })

/******/ });