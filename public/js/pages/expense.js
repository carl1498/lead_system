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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/expense.js":
/***/ (function(module, exports) {

$(document).ready(function () {

    //INITIALIZE -- START

    var current_tab = 'Expense'; //Expense, Type, Particular
    var date_counter = true; //True = base on date range | False = show all expense
    var branch = 'All';
    var company = 'All';

    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger: 'hover'
    });

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;
    today_converted = yyyy + '-' + mm + '-' + dd;
    var start_date = today_converted;
    var end_date = today_converted;

    $('#daterange').daterangepicker({
        showDropdowns: true,
        linkedCalendars: false,
        startDate: today,
        endDate: today
    }, function (start, end) {
        start_date = start.format('YYYY-MM-DD');
        end_date = end.format('YYYY-MM-DD');
        refresh();
    });

    $(document).on('change', '#date_counter', function () {
        date_counter = $(this).is(':checked') ? true : false;
        refresh();
    });

    $("#expense_type_modal").on("hidden.bs.modal", function (e) {
        $('#expense_type_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });

    $("#expense_particular_modal").on("hidden.bs.modal", function (e) {
        $('#expense_particular_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });

    $("#expense_modal").on("hidden.bs.modal", function (e) {
        expense_modal_clear();
        $('#expense_continuous').bootstrapToggle('off');
    });

    function expense_modal_clear() {
        $('#expense_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#expense_modal').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#expense_continuous').bootstrapToggle('off');

    function disableTabs() {
        $('li.expense_pick').addClass('disabled').css('cursor', 'not-allowed');

        $('a.expense_pick').addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs() {
        $('li.expense_pick').removeClass('disabled').css('cursor', 'pointer');

        $('a.expense_pick').removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh() {
        disableTabs();
        if (current_tab == 'Expense') {
            refresh_expense_table();
        } else if (current_tab == 'Type') {
            refresh_type_table();
        } else if (current_tab == 'Particular') {
            //Particular
            refresh_particular_table();
        } else {
            refresh_cash_disbursement_table();
        }
    }

    $('.company_type_select, .branch_select').show();
    $('#company_type_select, #branch_select').next(".select2-container").show();

    $('.refresh_table').on('click', function () {
        refresh();
    });

    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function refresh_expense_table() {
        $('#expense_table').DataTable({
            initComplete: function initComplete(settings, json) {
                enableTabs();
            },
            dom: 'Bflrtip',
            buttons: [{ extend: 'excelHtml5', title: 'Expense',
                exportOptions: {
                    columns: ':visible'
                },
                footer: true }, 'colvis'],
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
            responsive: true,
            ajax: {
                url: '/view_expense',
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    date_counter: date_counter,
                    branch: branch,
                    company: company
                }
            },
            columns: [{ data: 'type.name', name: 'type' }, { data: 'particular', name: 'particular' }, { data: 'branch.name', name: 'branch' }, { data: 'company_type.name', name: 'company' }, { data: 'date', name: 'date' }, { data: 'amount', name: 'amount' }, { data: 'vat', name: 'vat' }, { data: 'input_tax', name: 'input_tax' }, { data: 'check_voucher', name: 'check_voucher' }, { data: 'remarks', name: 'remarks' }, { data: 'action', orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }, { className: "text-right", targets: [5, 7] }],
            order: [4, 'desc'],
            footerCallback: function footerCallback(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function intVal(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                // Total over all pages
                total = api.column(5).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                // Total over all pages
                total2 = api.column(7).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                // Total over this page
                pageTotal = api.column(5, { page: 'current' }).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                // Update footer
                $(api.column(5).footer()).html('₱' + total.toFixed(2));

                // Total over this page
                pageTotal2 = api.column(7, { page: 'current' }).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                // Update footer
                $(api.column(7).footer()).html('₱' + total2.toFixed(2));
            }
        });
    }

    function refresh_type_table() {
        var type_table = $('#type_table').DataTable({
            initComplete: function initComplete(settings, json) {
                enableTabs();
            },
            dom: 'Bflrtip',
            buttons: [{ extend: 'excelHtml5', title: 'Expense Type',
                exportOptions: {
                    columns: ':visible'
                },
                footer: true }, 'colvis'],
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_expense_type',
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    date_counter: date_counter,
                    branch: branch,
                    company: company
                }
            },
            columns: [{ data: 'name', name: 'name' }, { data: 'total', name: 'total' }, { data: 'action', orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }, { className: "text-right", targets: 1 }],
            footerCallback: function footerCallback(row, data, start, end, display) {
                var api = this.api(),
                    data;

                // Remove the formatting to get integer data for summation
                var intVal = function intVal(i) {
                    return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                };

                // Total over all pages
                total = api.column(1).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                // Total over this page
                pageTotal = api.column(1, { page: 'current' }).data().reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

                // Update footer
                $(api.column(1).footer()).html('₱' + total.toFixed(2));
            }
        });
    }

    function refresh_particular_table() {
        var particular_table = $('#particular_table').DataTable({
            initComplete: function initComplete(settings, json) {
                enableTabs();
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_expense_particular'
            },
            columns: [{ data: 'name', name: 'name' }, { data: 'tin', name: 'tin' }, { data: 'address', name: 'address' }, { data: 'action', orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }]
        });
    }

    refresh();
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

    //DATATABLES -- END

    //FUNCTIONS -- START

    $(document).on('click', '.expense_pick', function () {
        current_tab = $(this).text();
    });

    $(document).on('click', '.add_expense', function () {
        if (current_tab == 'Particular') {
            $('#p_add_edit').val('add');
            $('#expense_particular_modal').modal('toggle');
            $('#expense_particular_modal').modal('show');
        } else if (current_tab == 'Type') {
            $('#t_add_edit').val('add');
            $('#expense_type_modal').modal('toggle');
            $('#expense_type_modal').modal('show');
        } else {
            //Expense
            $('#e_add_edit').val('add');
            $('#expense_modal').modal('toggle');
            $('#expense_modal').modal('show');
        }
    });

    $(document).on('click', '.edit_expense_type', function () {
        var id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_expense_type/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#t_id').val(data.id);
                $('#t_add_edit').val('edit');
                $('#expense_type_name').val(data.name);
                $('#expense_type_modal').modal('show');
            }
        });
    });

    $(document).on('click', '.edit_expense_particular', function () {
        var id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_expense_particular/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#p_id').val(data.id);
                $('#p_add_edit').val('edit');
                $('#expense_particular_name').val(data.name);
                $('#expense_particular_tin').val(data.tin);
                $('#expense_particular_address').val(data.address);
                $('#expense_particular_modal').modal('show');
            }
        });
    });

    $(document).on('click', '.edit_expense', function () {
        var id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_expense/' + id,
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#e_id').val(data.id);
                $('#e_add_edit').val('edit');
                $('#expense_type').val(data.expense_type_id).trigger('change');
                $('#expense_particular').val(data.expense_particular_id).trigger('change');
                $('#branch').val(data.branch_id).trigger('change');
                $('#company_type').val(data.lead_company_type_id).trigger('change');
                $('#date').val(data.date);
                $('#amount').val(data.amount);
                $('#vat').val(data.vat).trigger('change');
                $('#input_tax').val(data.input_tax);
                $('#check_voucher').val(data.check_voucher);
                $('#remarks').val(data.remarks);
                $('#expense_modal').modal('show');
            }
        });
    });

    $(document).on('submit', '#expense_type_form', function (e) {
        e.preventDefault();

        var input = $('.save_expense_type');
        var button = document.getElementsByClassName("save_expense_type")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_expense_type',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#expense_type_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#expense_particular_form', function (e) {
        e.preventDefault();

        var input = $('.save_expense_particular');
        var button = document.getElementsByClassName("save_expense_particular")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_expense_particular',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#expense_particular_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#expense_form', function (e) {
        e.preventDefault();

        var input = $('.save_expense');
        var button = document.getElementsByClassName("save_expense")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_expense',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                if ($('#expense_continuous').is(':checked')) {
                    expense_modal_clear();
                    $('#e_add_edit').val('add');
                } else {
                    $('#expense_modal').modal('hide');
                }
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function error(data) {
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.delete_expense_type', function () {
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
                                title: 'Warning',
                                text: 'Are you sure?',
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
                                        url: '/delete_expense_type',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        method: 'get',
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This expense type has been Deleted', 'success', 'glyphicon-ok');
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

    $(document).on('click', '.delete_expense_particular', function () {
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
                                title: 'Warning',
                                text: 'Are you sure?',
                                type: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Yes, delete it!'
                            }).then(function (result) {
                                console.log(password);
                                if (result.value) {
                                    $.ajax({
                                        headers: {
                                            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        url: '/delete_expense_particular',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        method: 'get',
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This expense particular has been Deleted', 'success', 'glyphicon-ok');
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

    $(document).on('click', '.delete_expense', function () {
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
                                title: 'Warning',
                                text: 'Are you sure?',
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
                                        url: '/delete_expense',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        method: 'get',
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This expense has been Deleted', 'success', 'glyphicon-ok');
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

    $('#amount').keyup(function () {
        if ($('#vat').val() == 'VAT') {
            $('#input_tax').val(parseFloat($(this).val() / 1.12 * 0.12).toFixed(2));
        } else {
            $('#input_tax').val(0);
        }
    });

    $(document).on('change', '#vat', function () {
        if ($(this).val() == 'VAT') {
            if ($('#amount').val() != '') {
                $('#input_tax').val(parseFloat($('#amount').val() / 1.12 * 0.12).toFixed(2));
            }
        } else {
            $('#input_tax').val(0);
        }
    });

    $(document).on('change', '#vat', function () {
        if ($(this).val() == 'VAT') {
            if ($('#amount').val() != '') {
                $('#input_tax').val(parseFloat($('#amount').val() / 1.12 * 0.12).toFixed(2));
            }
        } else {
            $('#input_tax').val(0);
        }
    });

    $(document).on('change', '#company_type_select', function () {
        company = $(this).val();
        refresh();
    });

    $(document).on('change', '#branch_select', function () {
        branch = $(this).val();
        refresh();
    });

    $('#expense_type').select2({
        allowClear: true,
        placeholder: 'Select Type',
        ajax: {
            url: "/expenseTypeAll",
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

    $('#expense_particular').select2({
        allowClear: true,
        placeholder: 'Select Particular',
        ajax: {
            url: "/expenseParticularAll",
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

    function refresh_cash_disbursement_table() {
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/view_cash_disbursement',
            data: {
                start_date: start_date,
                end_date: end_date,
                date_counter: date_counter,
                branch: branch,
                company: company
            },
            method: 'get',
            dataType: 'json',
            success: function success(data) {
                $('#cash_disbursement_table').empty();

                var html = '';
                var type = '';
                var type_total = '';

                html += '<thead>';

                for (var x = 0; x < data.expense_type.length; x++) {
                    type += '<th class="expense_types">' + data.expense_type[x].name + '</th>';
                    type_total += '<th class="expense_types">' + data.expense_type[x].expense_type_total + '</th>';
                }

                html += '\n                <tr>\n                    <th rowspan="2" style="width: 100px;">Date</th>\n                    <th rowspan="2" style="width: 130px;">Check Voucher</th>\n                    <th rowspan="2" style="width: 350px;">Particulars</th>\n                    <th rowspan="2" style="width: 130px;">TIN Number</th>\n                    <th rowspan="2" style="width: 350px;">Address</th>\n                    <th style="width: 150px;">Total Invoice</th>\n                    <th style="width: 150px;">Non Vat</th>\n                    <th style="width: 150px;">Vatable Amount</th>\n                    <th style="width: 150px;">Input Tax</th>' + type + '</tr>\n                <tr>\n                    <th>' + data.total + '</th>\n                    <th>' + data.non_vat + '</th>\n                    <th>' + data.vat + '</th>\n                    <th>' + data.input_tax + '</th>' + type_total + '</tr>';

                html += '</thead>';

                html += '<tbody>';
                for (var _x = 0; _x < data.expense.length; _x++) {
                    var particular = '';
                    for (var y = 0; y < data.expense_type.length; y++) {
                        particular += '<td style="text-align:right;">' + data.expense_particular_type_total[_x][y] + '</td>';
                    }
                    html += '<tr>' + '<td>' + data.expense[_x].date + '</td>' + '<td>' + (data.expense[_x].check_voucher ? data.expense[_x].check_voucher : '') + '</td>' + '<td>' + data.expense[_x].particular.name + '</td>' + '<td>' + (data.expense[_x].particular.tin ? data.expense[_x].particular.tin : '') + '</td>' + '<td>' + (data.expense[_x].particular.address ? data.expense[_x].particular.address : '') + '</td>' + '<td style="text-align:right;">' + data.expense[_x].total_invoice + '</td>' + '<td style="text-align:right;">' + data.expense[_x].non_vat_total + '</td>' + '<td style="text-align:right;">' + data.expense[_x].vat_total + '</td>' + '<td style="text-align:right;">' + data.expense[_x].input_tax_total + '</td>' + particular + '</tr>';
                }

                html += '</tbody>';

                $('#cash_disbursement_table').append(html);

                $("#cash_disbursement_table").tableExport({
                    position: 'top',
                    formats: ['xlsx'],
                    bootstrap: true
                });

                enableTabs();
            }
        });
    }

    //FUNCTIONS -- END
});

/***/ }),

/***/ 11:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/js/pages/expense.js");


/***/ })

/******/ });