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
/******/ 	return __webpack_require__(__webpack_require__.s = 4);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/pages/invoice.js":
/***/ (function(module, exports) {

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

$(document).ready(function () {

    //INITIALIZE -- START

    var type_select = $('#type_select').val();
    var book_type_select = $('#book_type_select').val();
    var invoice_select = $('#invoice_select').val();
    var invoice_id, book_type;
    var current_tab = 'Invoice';
    var modal_close = true;

    $("#invoice_modal").on("hidden.bs.modal", function (e) {
        $('#invoice_form :input.required').each(function () {
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        modal_close = true;
    });

    $('#add_books_modal').on('hidden.bs.modal', function (e) {
        $(this).find("input,textarea,select").val('').end().trigger('change');
        $('#invoice_add_book').val('').trigger('change');
        $('#book_type_add_book').val('').trigger('change');
        $('#book_type_add_book, #quantity, #starting, #remarks').prop('disabled', true);
        modal_close = true;
    });

    $('.select2').select2();

    $('#invoice_add_book').select2({
        placeholder: 'Select Invoice',
        ajax: {
            url: "/invoiceAll",
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

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger: 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    function disableTabs() {
        $('li.invoice_pick').addClass('disabled').css('cursor', 'not-allowed');
        $('a.invoice_pick').addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs() {
        $('li.invoice_pick').removeClass('disabled').css('cursor', 'pointer');
        $('a.invoice_pick').removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh() {
        disableTabs();

        if (current_tab == 'Invoice') {
            $('.invoice_select').hide();
            $('#invoice_select').next(".select2-container").hide();
            $('.book_type_select').hide();
            $('#book_type_select').next(".select2-container").hide();

            $('.type_select').show();
            $('#type_select').next(".select2-container").show();
        } else if (current_tab == 'Add Book History') {
            $('.type_select').hide();
            $('#type_select').next(".select2-container").hide();

            $('.invoice_select').show();
            $('#invoice_select').next(".select2-container").show();
            $('.book_type_select').show();
            $('#book_type_select').next(".select2-container").show();
        }

        switch (current_tab) {
            case 'Invoice':
                refresh_invoice();break;
            case 'Add Book History':
                refresh_add_book_history();break;
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

    function refresh_invoice() {

        type_select = $('#type_select').val();

        var invoice_table = $('#invoice_table').DataTable({
            initComplete: function initComplete(settings, json) {
                enableTabs();
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_invoice/{type_select}',
                data: { type_select: type_select }
            },
            columns: [{ data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no' }, { data: 'book_1', name: 'book_1' }, { data: 'wb_1', name: 'wb_1' }, { data: 'book_2', name: 'book_2' }, { data: 'wb_2', name: 'wb_2' }, { data: 'kanji', name: 'kanji' }, { data: 'book_1_ssw', name: 'book_1_ssw' }, { data: 'wb_1_ssw', name: 'wb_1_ssw' }, { data: 'book_2_ssw', name: 'book_2_ssw' }, { data: 'wb_2_ssw', name: 'wb_2_ssw' }, { data: 'kanji_ssw', name: 'kanji_ssw' }, { data: 'created_at', name: 'date' }, { data: 'action', orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            order: [[11, 'desc']]
        });
    }

    function refresh_add_book_history() {

        book_type_select = $('#book_type_select').val();
        invoice_select = $('#invoice_select').val();

        var add_books_table = $('#add_books_table').DataTable({
            initComplete: function initComplete(settings, json) {
                enableTabs();
            },
            stateSave: true,
            stateSaveCallback: function stateSaveCallback(settings, data) {
                localStorage.setItem('DataTables_' + settings.sInstance, JSON.stringify(data));
            },
            stateLoadCallback: function stateLoadCallback(settings) {
                return JSON.parse(localStorage.getItem('DataTables_' + settings.sInstance));
            },
            stateLoadParams: function stateLoadParams(settings, data) {
                if (data.order) delete data.order;
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
            responsive: true,
            ajax: {
                url: '/viewAddBooks',
                data: {
                    book_type_select: book_type_select,
                    invoice_select: invoice_select
                }
            },
            columns: [{ data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no' }, { data: 'book_type.description', name: 'book_type' }, { data: 'previous_pending', name: 'previous_pending' }, { data: 'quantity', name: 'quantity' }, { data: 'pending', name: 'pending' }, { data: 'book_range', name: 'book_range' }, { data: 'created_at', name: 'date' }, { data: 'remarks', name: 'remarks' }, { data: 'action', orderable: false, searchable: false }],
            columnDefs: [{ defaultContent: "", targets: "_all" }],
            order: [[6, 'desc']]
        });
    }

    refresh();

    //DATATABLES -- END


    //FUNCTIONS -- START

    $('.invoice_pick').on('click', function () {
        if (!$(this).hasClass('disabled')) {
            current_tab = $(this).text();
        }
    });

    //Invoice Reference No. Select
    $(document).on('change', '#invoice_add_book', function () {
        invoice_id = $('#invoice_add_book').val();
        $('#book_type_add_book').val('').trigger('change');
        $('#book_type_add_book').prop('disabled', false);
        $('#quantity, #starting, #remarks').prop('disabled', true);
        $('#quantity, #starting, #start, #pending, #end, #remarks').val('');
        showBooks();
    });

    $(document).on('submit', '#add_books_form', function (e) {
        e.preventDefault();

        var input = $('.save_books');
        var button = document.getElementsByClassName("save_books")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_books',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#add_books_modal').modal('hide');
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

    //Book Type Select
    $(document).on('change', '#book_type_add_book', function (e) {
        e.preventDefault();
        if ($('#book_type_add_book').val()) {
            $('#quantity, #starting, #start, #pending, #end, #remarks').val('');
            $('#quantity').prop('disabled', false);
            $('#remarks').prop('disabled', false);
            invoice_id = $('#invoice_add_book').val();
            book_type = $('#book_type_add_book').val();

            $.ajax({
                url: '/getPending',
                method: 'get',
                data: {
                    invoice_id: invoice_id,
                    book_type: book_type
                },
                dataType: 'text',
                success: function success(data) {
                    $('#previous_pending').val(parseInt(data));
                    $('#pending').val(parseInt(data));
                }
            });

            $.ajax({
                url: '/getStarting/' + book_type,
                method: 'get',
                data: {
                    book_type: book_type
                },
                dataType: 'text',
                success: function success(data) {
                    $('#starting').val(data);
                    $('#start').val(data);
                    $('#starting').prop('disabled', false);
                }
            });
        } else {
            $('#previous_pending').val('');
        }
    });

    $('#starting').keyup(function () {
        $('#start').val($('#starting').val());
        $('#end').val(parseInt($('#start').val()) + (parseInt($('#quantity').val()) - 1));
    });

    //formula for total pending
    $('#quantity').keyup(function () {
        if ($('#quantity').val() == '') {
            $('#pending').val($('#previous_pending').val());
            $('#end').val('');
            return;
        }
        if ($('#previous_pending').val() - $('#quantity').val() < 0) {
            $('#quantity').val($('#previous_pending').val());
            $('#pending').val($('#previous_pending').val() - $('#quantity').val());
            $('#end').val(parseInt($('#start').val()) + (parseInt($('#quantity').val()) - 1));
            return;
        }
        $('#pending').val($('#previous_pending').val() - $('#quantity').val());
        $('#end').val(parseInt($('#start').val()) + (parseInt($('#quantity').val()) - 1));
    });

    //SELECT 2
    function showBooks() {
        var _ajax;

        $('#book_type_add_book').select2({
            placeholder: 'Select Book',
            ajax: (_ajax = {
                url: "/bookAll/" + invoice_id,
                data: { invoice_id: invoice_id },
                dataType: 'json'

            }, _defineProperty(_ajax, 'data', function data(params) {
                return {
                    name: params.term,
                    page: params.page
                };
            }), _defineProperty(_ajax, 'processResults', function processResults(data) {
                return {
                    results: data.results
                };
            }), _ajax)
        });
    }

    //INVOICE -- START

    $(document).on('change', '#type_select', function () {
        refresh();
    });

    //Open Add Invoice Modal
    $('.add_invoice').on('click', function () {
        modal_close = false;
        $('#invoice_modal').modal('toggle');
        $('#invoice_modal').modal('show');
    });

    //Open Add Books Modal
    $('.add_books').on('click', function () {
        modal_close = false;
        $('#add_books_modal').modal('toggle');
        $('#add_books_modal').modal('show');
    });

    //Save Invoice
    $(document).on('submit', '#invoice_form', function (e) {
        e.preventDefault();

        var input = $('.save_invoice');
        var button = document.getElementsByClassName("save_invoice")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_invoice',
            method: 'POST',
            data: $(this).serialize(),
            success: function success(data) {
                if (data == 'false') {
                    swal("Duplicate!", "Invoice reference no. already existing", "error");
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#invoice_modal').modal('hide');
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

    $(document).on('click', '.delete_invoice', function () {
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
                                text: 'This may delete data of books added for this invoice.',
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
                                        url: '/delete_invoice/' + id,
                                        data: { password: password },
                                        method: 'get',
                                        type: 'json',
                                        success: function success(data) {
                                            notif('Success!', 'This invoice has been Deleted', 'success', 'glyphicon-ok');
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

    //INVOICE -- END

    //ADD BOOK -- START

    $(document).on('change', '#book_type_select', function () {
        refresh();
    });

    $(document).on('change', '#invoice_select', function () {
        refresh();
    });

    $(document).on('click', '.delete_add_book', function () {
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
                                text: 'This may delete data of books.',
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
                                        url: '/delete_add_book/' + id,
                                        data: { password: password },
                                        method: 'get',
                                        type: 'json',
                                        success: function success(data) {
                                            if (data == 1) {
                                                swal('Error!', 'Some books within range are already released or not available', 'error');
                                                return;
                                            }
                                            notif('Success!', 'This add book history has been Deleted', 'success', 'glyphicon-ok');
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

    //ADD BOOK -- END

    //FUNCTIONS -- END
});

/***/ }),

/***/ 4:
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__("./resources/js/pages/invoice.js");


/***/ })

/******/ });