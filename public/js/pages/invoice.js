$(document).ready(function(){

    //INITIALIZE -- START

    var invoice_select = $('#invoice_select').val();
    var invoice_id, book_type;
    var current_tab = 'Invoice';

    $("#invoice_modal").on("hidden.bs.modal", function(e){
        $('#invoice_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });

    $('#add_books_modal').on('hidden.bs.modal', function(e){
        $(this).find("input,textarea,select").val('').end().trigger('change');
        $('#invoice_add_book').val('').trigger('change');
        $('#book_type_add_book').val('').trigger('change');
        $('#book_type_add_book, #quantity, #starting, #remarks').prop('disabled', true);
    });

    $('.select2').select2();

    $('#invoice_add_book').select2({
        placeholder: 'Select Invoice',
        ajax: {
            url: "/invoiceAll",
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

    $('input, select').attr('autocomplete', 'off');
    
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    //INITIALIZE -- END


    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function refresh_invoice(){

        invoice_select = $('#invoice_select').val();

        var invoice_table = $('#invoice_table').DataTable({
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_invoice/{invoice_select}',
                data: {invoice_select: invoice_select}
            },
            columns: [
                {data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'book_1', name: 'book_1'},
                {data: 'wb_1', name: 'wb_1'},
                {data: 'book_2', name: 'book_2'},
                {data: 'wb_2', name: 'wb_2'},
                {data: 'kanji', name: 'kanji'},
                {data: 'book_1_ssv', name: 'book_1_ssv'},
                {data: 'wb_1_ssv', name: 'wb_1_ssv'},
                {data: 'book_2_ssv', name: 'book_2_ssv'},
                {data: 'wb_2_ssv', name: 'wb_2_ssv'},
                {data: 'kanji_ssv', name: 'kanji_ssv'},
                {data: 'created_at', name: 'date'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                { width: 120, targets: 0 }, //invoice ref no
                { width: 60, targets: 1 }, //book 1
                { width: 60, targets: 2 }, //wb 1
                { width: 60, targets: 3 }, //book 2
                { width: 60, targets: 4 }, //wb 2
                { width: 60, targets: 5 }, //kanji
                { width: 90, targets: 6 }, //book 1 ssv
                { width: 90, targets: 7 }, //wb 1 ssv
                { width: 90, targets: 8 }, //book 2 ssv
                { width: 90, targets: 9 }, //wb 2 ssv
                { width: 90, targets: 10 }, //kanji
                { width: 130, targets: 11 }, //date
                { width: 80, targets: 12 }, //action
            ],
            order: [[
                11, 'desc'
            ]]
        });
    }

    function refresh_add_book_history(){

        var add_books_table = $('#add_books_table').DataTable({
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
            fixedColumns: {
                leftColumns: 2
            },
            responsive: true,
            ajax: {
                url: '/viewAddBooks'
            },
            columns: [
                {data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'book_type.description', name: 'book_type'},
                {data: 'previous_pending', name: 'previous_pending'},
                {data: 'quantity', name: 'quantity'},
                {data: 'pending', name: 'pending'},
                {data: 'book_range', name: 'book_range'},
                {data: 'created_at', name: 'date'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                { width: 120, targets: 0 }, //invoice ref no
                { width: 180, targets: 1 }, //book type
                { width: 60, targets: 2 }, //previous pending
                { width: 60, targets: 3 }, //quantity
                { width: 60, targets: 4 }, //pending
                { width: 120, targets: 5 }, //book no range
                { width: 130, targets: 6 }, //date
                { width: 200, targets: 7 }, //remarks
                { width: 80, targets: 8 }, //action
            ],
            order: [[
                6, 'desc'
            ]]
        })

    }

    refresh_invoice();
    refresh_add_book_history();

    //DATATABLES -- END


    //FUNCTIONS -- START

    $('.invoice_pick').on('click', function(){
        current_tab = $(this).text();
        if(current_tab == 'Invoice'){
            $('.invoice_select').show();
            $('#invoice_select').next(".select2-container").show();
            refresh_invoice();
        }else{
            $('.invoice_select').hide();
            $('#invoice_select').next(".select2-container").hide();
            refresh_add_book_history();
        }
    });

    //Invoice Reference No. Select
    $(document).on('change', '#invoice_add_book', function(){
        invoice_id = $('#invoice_add_book').val();
        $('#book_type_add_book').val('').trigger('change');
        $('#book_type_add_book').prop('disabled', false);
        $('#quantity, #starting, #remarks').prop('disabled', true);
        $('#quantity, #starting, #start, #pending, #end, #remarks').val('');
        showBooks();
    });

    $(document).on('submit', '#add_books_form', function(e){
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
            success: function(data){
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#add_books_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_invoice();
                refresh_add_book_history();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        })
    });

    //Book Type Select
    $(document).on('change', '#book_type_add_book', function(e){
        e.preventDefault();
        if($('#book_type_add_book').val()){
            $('#quantity, #starting, #start, #pending, #end, #remarks').val('');
            $('#quantity').prop('disabled', false);
            $('#remarks').prop('disabled', false);
            invoice_id = $('#invoice_add_book').val();
            book_type = $('#book_type_add_book').val();
            
            $.ajax({
                url: '/getPending/'+invoice_id+'/'+book_type,
                method: 'get',
                data: {
                    invoice_id: invoice_id,
                    book_type: book_type                    
                },
                dataType: 'text',
                success: function(data){
                    $('#previous_pending').val(data);
                    $('#pending').val(data);
                }
            });

            $.ajax({
                url: '/getStarting/'+book_type,
                method: 'get',
                data: {
                    book_type: book_type
                },
                dataType: 'text',
                success: function(data){
                    $('#starting').val(data);
                    $('#start').val(data);
                    $('#starting').prop('disabled', false);
                }
            });
        }
        else{
            $('#previous_pending').val('');
        }
    });

    $('#starting').keyup(function(){
        $('#start').val($('#starting').val());
        $('#end').val(parseInt($('#start').val()) + (parseInt($('#quantity').val()) - 1));
    });

    //formula for total pending
    $('#quantity').keyup(function(){
        if($('#quantity').val() == ''){
            $('#pending').val($('#previous_pending').val());
            $('#end').val('');
            return;
        }
        if($('#previous_pending').val() - $('#quantity').val() < 0){
            $('#quantity').val($('#previous_pending').val());
            $('#pending').val($('#previous_pending').val() - $('#quantity').val());
            $('#end').val(parseInt($('#start').val()) + (parseInt($('#quantity').val()) - 1));
            return;
        }
        $('#pending').val($('#previous_pending').val() - $('#quantity').val());
        $('#end').val(parseInt($('#start').val()) + (parseInt($('#quantity').val()) - 1));
    });

    //SELECT 2
    function showBooks(){
        $('#book_type_add_book').select2({
            placeholder: 'Select Book',
            ajax: {
                url: "/bookAll/"+invoice_id,
                data: {invoice_id: invoice_id},
                dataType: 'json',

                data: function (params){
                    return {
                        name: params.term,
                        page: params.page
                    }
                },
                processResults: function (data){
                    return {
                        results:data.results      
                    }
                }
            },
        });
    }


    //INVOICE -- START

    $(document).on('change', '#invoice_select', function(){
        refresh_invoice();
    });

    //Open Add Invoice Modal
    $('.add_invoice').on('click', function(){            
        $('#invoice_modal').modal('toggle');
        $('#invoice_modal').modal('show');
    });

    //Open Add Books Modal
    $('.add_books').on('click', function(){
        $('#add_books_modal').modal('toggle');
        $('#add_books_modal').modal('show');
    });

    //Save Invoice
    $(document).on('submit', '#invoice_form', function(e){
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
            success: function(data){
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#invoice_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_invoice();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        })
    });

    $(document).on('click', '.delete_invoice', function(){
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
                                title: 'Warning',
                                text: 'This may delete data of books added for this invoice.',
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
                                        url: '/delete_invoice/'+id,
                                        method: 'get',
                                        type: 'json',
                                        success:function(data){
                                            swal('Success!', 'This invoice has been Deleted', 'success');
                                            refresh_invoice();
                                        }
                                    })
                                }
                            });
                        }
                    }
                });
            },
        });
    });

    //INVOICE -- END

    //ADD BOOK -- START
    $(document).on('click', '.delete_add_book', function(){
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
                                title: 'Warning',
                                text: 'This may delete data of books.',
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
                                        url: '/delete_add_book/'+id,
                                        method: 'get',
                                        type: 'json',
                                        success:function(data){
                                            if(data == 1){
                                                swal('Error!', 'Some books within range are already released or not available', 'error');
                                                return;
                                            }
                                            swal('Success!', 'This add book history has been Deleted', 'success');
                                            refresh_add_book_history();
                                        }
                                    })
                                }
                            });
                        }
                    }
                });
            },
        });
    });

    //ADD BOOK -- END

    //FUNCTIONS -- END
});