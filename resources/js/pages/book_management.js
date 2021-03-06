$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Branch';
    var book_type;
    var books_pick;
    var branch_id;
    var student_id;
    var book_status = $('#status_select').val();
    var book_type_select = $('#book_type_select').val();
    var student_status_select = $('#student_status_select').val();
    var program_select = $('#program_select').val();
    var branch_select = $('#branch_select').val();
    var invoice_select = $('#invoice_select').val();
    var modal_close = true;

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $('#request_books_modal').on('hidden.bs.modal', function(e){
        $(this).find("input,textarea,select").val('').end();
        $('#request_book').val('').trigger('change');
        $('#request_quantity, #request_remarks').prop('disabled', true);
        modal_close = true;
    });

    $('#release_books_modal').on('hidden.bs.modal', function(e){
        $(this).find("input,textarea,select").val('').end();
        $('#release_book, #release_branch').val('').trigger('change');
        $('#release_quantity, #release_book, #release_starting, #release_remarks').prop('disabled', true);
        modal_close = true;
    });

    $('#assign_books_modal').on('hidden.bs.modal', function(e){
        assign_modal_clear();
        $('#assign_continuous').bootstrapToggle('off')
        modal_close = true;
    });

    function assign_modal_clear(){
        $('#assign_books_modal').find("input,textarea,select").val('').end();
        $('#assign_student_name, #assign_book_type, #assign_book').val('').trigger('change');
        $('#assign_book_type, #assign_book').prop('disabled', true);
    }

    $('#assign_continuous').bootstrapToggle('off')

    $('.select2').select2();

    $('#request_book').select2({
        placeholder: "Select Book"
    });

    $('#release_branch').select2({
        placeholder: "Select Branch"
    });

    $('.books_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_tab = $(this).text();
        }
    });

    function disableTabs(){
        $(`li.books_pick`).addClass('disabled').css('cursor', 'not-allowed');
        $(`a.books_pick`).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.books_pick`).removeClass('disabled').css('cursor', 'pointer');
        $(`a.books_pick`).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh(){
        disableTabs();

        if(current_tab != 'Branch' && current_tab != 'Student' && current_tab != 'SSW' ){
            $('.book_type_select').show();
            $('#book_type_select').next(".select2-container").show();
        }
        else{
            $('.book_type_select').hide();
            $('#book_type_select').next(".select2-container").hide();
        }

        if(current_tab != 'Branch'){
            $('.branch_select').show();
            $('#branch_select').next(".select2-container").show();

            $('.status_select').hide();
            $('#status_select').next(".select2-container").hide();
        }

        if(current_tab != 'Student' && current_tab != 'SSW' &&
            current_tab != 'Request History' && current_tab != 'Release History'){
            $('.invoice_select').show();
            $('#invoice_select').next(".select2-container").show();
        }
        else{
            $('.invoice_select').hide();
            $('#invoice_select').next(".select2-container").hide();
        }

        if(current_tab != 'Student' && current_tab != current_tab != 'SSW'){
            $('.student_status_select').hide();
            $('#student_status_select').next(".select2-container").hide();
            $('.program_select').hide();
            $('#program_select').next(".select2-container").hide();
        }

        switch(current_tab){
            case 'Branch'           : refresh_books_branch_table(); break;
            case 'Student'          : refresh_books_student_table(); break;
            case 'SSW'              : refresh_books_ssw_student_table(); break;
            case 'Books'            : refresh_books(); break;
            case 'Request History'  : refresh_request_books(); break;
            case 'Release History'  : refresh_release_books(); break;
            case 'Assign History'   : refresh_assign_books(); break;
            case 'Return History'   : refresh_return_books(); break;
            case 'Lost History'     : refresh_lost_books(); break;
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

    function refresh_request_books(){
        var request_books_table = $('#books_request_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 3
            },
            responsive: true,
            ajax: {
                url: '/view_request_books',
                data: {
                    book_type_select: book_type_select,
                    branch_select: branch_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'pending_request.branch.name', name: 'branch'},
                {data: 'pending_request.book_type.description', name: 'book'},
                {data: 'previous_pending', name: 'previous_pending'},
                {data: 'quantity', name: 'quantity'},
                {data: 'pending', name: 'pending'},
                {data: 'created_at', name: 'date'},
                {data: 'status', name: 'status'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            order: [[
                6, 'desc'
            ]]
        });
    }

    function refresh_release_books(){
        var release_books_table = $('#books_release_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 3
            },
            responsive: true,
            ajax: {
                url: '/view_release_books',
                data: {
                    book_type_select: book_type_select,
                    branch_select: branch_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'id', name: 'id'},
                {data: 'pending_request.branch.name', name: 'branch'},
                {data: 'pending_request.book_type.description', name: 'book'},
                {data: 'previous_pending', name: 'previous_pending'},
                {data: 'quantity', name: 'quantity'},
                {data: 'pending', name: 'pending'},
                {data: 'book_range', name: 'book_range'},
                {data: 'created_at', name: 'date'},
                {data: 'status', name: 'status'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            order: [[
                7, 'desc'
            ]]
        });
    }
    
    function refresh_assign_books(){
        var assign_books_table = $('#books_assign_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_assign_books',
                data: {
                    book_type_select: book_type_select,
                    branch_select: branch_select,
                    invoice_select: invoice_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'student_name', name: 'name'},
                {data: 'student.branch.name', name: 'branch'},
                {data: 'books.book_type.description', name: 'book'},
                {data: 'books.name', name: 'book_no'},
                {data: 'books.reference_no.invoice_ref_no', name: 'reference_no'},
                {data: 'created_at', name: 'date'},
            ],
            order: [[
                5, 'desc'
            ]]
        });
    }

    function refresh_books(){
        var books_table = $('#books_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 3
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            responsive: true,
            ajax: '/view_books/'+book_type_select,
            ajax: {
                url: '/view_books',
                data: {
                    book_type_select: book_type_select,
                    branch_select: branch_select,
                    invoice_select: invoice_select,
                }
            },
            columns: [
                {data: 'book_type.description', name: 'book_type'},
                {data: 'name', name: 'book_no'},
                {data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'branch.name', name: 'branch'},
                {data: 'status', name: 'status'},
                {data: 'student_name', name: 'student_name'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });
    }

    function refresh_books_student_table(){
        $('.student_status_select').show();
        $('#student_status_select').next(".select2-container").show();
        $('.program_select').show();
        $('#program_select').next(".select2-container").show();

        var books_student_table = $('#books_student_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_student_books',
                data:{ 
                    student_status_select: student_status_select,
                    program_select: program_select,
                    branch_select: branch_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'student_name', name: 'student_name'},
                {data: 'branch.name', name: 'branch'},
                {data: 'book_1', name: 'book_1'},
                {data: 'wb_1', name: 'wb_1'},
                {data: 'book_2', name: 'book_2'},
                {data: 'wb_2', name: 'wb_2'},
                {data: 'kanji', name: 'kanji'},
                {data: 'program.name', name: 'program', defaultContent: ''},
                {data: 'status', name: 'status'},
                {data: 'departure', name: 'departure'},
            ],
            order: [[7, 'asc']]
        });
    }

    function refresh_books_ssw_student_table(){
        $('.student_status_select').show();
        $('#student_status_select').next(".select2-container").show();
        $('.program_select').show();
        $('#program_select').next(".select2-container").show();

        var books_ssw_student_table = $('#books_ssw_student_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_ssw_student_books',
                data:{ 
                    student_status_select: student_status_select,
                    program_select: program_select,
                    branch_select: branch_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'student_name', name: 'student_name'},
                {data: 'branch.name', name: 'branch'},
                {data: 'book_1_ssw', name: 'book_1_ssw'},
                {data: 'wb_1_ssw', name: 'wb_1_ssw'},
                {data: 'book_2_ssw', name: 'book_2_ssw'},
                {data: 'wb_2_ssw', name: 'wb_2_ssw'},
                {data: 'kanji_ssw', name: 'kanji_ssw'},
                {data: 'program.name', name: 'program', defaultContent: ''},
                {data: 'status', name: 'status'},
            ],
        });
    }

    function refresh_books_branch_table(){
        $('.status_select').show();
        $('#status_select').next(".select2-container").show();

        $('.branch_select').hide();
        $('#branch_select').next(".select2-container").hide();


        var books_branch_table = $('#books_branch_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_branch_books',
                data: {
                    book_status: book_status,
                    invoice_select: invoice_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'name', name: 'branch'},
                {data: 'book_1', name: 'book_1'},
                {data: 'wb_1', name: 'wb_1'},
                {data: 'book_2', name: 'book_2'},
                {data: 'wb_2', name: 'wb_2'},
                {data: 'kanji', name: 'kanji'},
                {data: 'book_1_ssw', name: 'book_1_ssw'},
                {data: 'wb_1_ssw', name: 'wb_1_ssw'},
                {data: 'book_2_ssw', name: 'book_2_ssw'},
                {data: 'wb_2_ssw', name: 'wb_2_ssw'},
                {data: 'kanji_ssw', name: 'kanji_ssw'},
            ],
        });
    }

    function refresh_lost_books(){
        var books_lost_table = $('#books_lost_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            scrollX: true,
            destroy: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_books_lost',
                data: {
                    book_type_select: book_type_select,
                    branch_select: branch_select,
                    invoice_select: invoice_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'books.book_type.description', name: 'book'},
                {data: 'books.name', name: 'book_no'},
                {data: 'books.reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'books.branch.name', name: 'branch'},
                {data: 'stud_id', name: 'student_name'},
                {data: 'created_at', name: 'date'},
            ],
            order: [[
                5, 'desc'
            ]]
        });
    }

    function refresh_return_books(){
        var books_return_table = $('#books_return_table').DataTable({
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
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_books_return',
                data: {
                    book_type_select: book_type_select,
                    branch_select: branch_select,
                    invoice_select: invoice_select,
                }
            },
            buttons: [
                {extend: 'excelHtml5', title: 'Books',
                exportOptions: {
                    columns: ':visible'
                }},
                'colvis'
            ],
            columns: [
                {data: 'books.book_type.description', name: 'book'},
                {data: 'books.name', name: 'book_no'},
                {data: 'books.reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'books.branch.name', name: 'branch'},
                {data: 'stud_id', name: 'student_name'},
                {data: 'created_at', name: 'date'},
            ],
            order: [[
                5, 'desc'
            ]]
        });
    }

    refresh();
    
    //DATATABLES -- END

    //FUNCTIONS -- START

    $('#status_select').on('change', function(){
        book_status = $(this).val();
        refresh();
    });

    $('#book_type_select').on('change', function(){
        book_type_select = $(this).val();
        refresh();
    });

    $('#student_status_select').on('change', function(){
        student_status_select = $(this).val();
        refresh();
    });

    $('#program_select').on('change', function(){
        program_select = $(this).val();
        refresh();
    });

    $('#branch_select').on('change', function(){
        branch_select = $(this).val();
        refresh();
    });
    
    $('#invoice_select').on('change', function(){
        invoice_select = $(this).val();
        refresh();
    });

    //REQUEST BOOKS -- START

    //Open Request Books Modal
    $('.request_books').on('click', function(){
        modal_close = false;
        $('#request_books_modal').modal('toggle');
        $('#request_books_modal').modal('show');
    });

    $('#request_book').on('change', function(e){
        e.preventDefault();

        book_type = $(this).val();
        if(book_type != ''){
            $.ajax({
                url: '/getRequestPending/'+book_type,
                method: 'get',
                dataType: 'text',
                success:function(data){
                    $('#request_previous_pending').val(data);
                    $('#request_pending').val(data);
                    $('#request_quantity, #request_remarks').prop('disabled', false);
                    $('#request_quantity, #request_remarks').val('');
                }
            });
        }
    });

    $('#request_quantity').keyup(function(){
        if($(this).val() == ''){
            $('#request_pending').val($('#request_previous_pending').val());
        }else{
            $('#request_pending').val(parseInt($('#request_previous_pending').val()) + parseInt($('#request_quantity').val()));
        }
    });

    $(document).on('submit', '#request_books_form', function(e){
        e.preventDefault();

        var input = $('.save_book_request');
        var button = document.getElementsByClassName("save_book_request")[0];

        button.disabled = true;
        input.html('SAVING...');

        book_type = $('#request_book').val();

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_book_request',
            data: $(this).serialize(),
            method: 'POST',
            dataType: 'text',
            success: function(data){
                $('#request_books_modal').modal('hide');
                notif('Success!', 'Books Requested!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.approve_request', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Approve Book Request?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/approve_book_request/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Book Request Approved!', '', 'success', 'glyphicon-ok');
                        refresh();
                    }
                })
            }
        });
    });

    $(document).on('click', '.deliver_request', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Book Request Delivered?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/delivered_book_request/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Book Request Delivered!', '', 'success', 'glyphicon-ok');
                        refresh();
                    }
                })
            }
        });
    });

    $(document).on('click', '.pending_request', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Book Request Back to Pending?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/pending_book_request/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Book Request Pending Again!', '', 'info', 'glyphicon-info-sign');
                        refresh();
                    }
                })
            }
        });
    });

    $(document).on('click', '.cancel_request', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Cancel Book Request?',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/cancel_book_request/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        if(data == 1){
                            swal('Cancel not allowed!', 'Quantity to cancel higher than current pending', 'info');
                            return;
                        }
                        notif('Book Request Cancelled!', '', 'info', 'glyphicon-info-sign');
                        refresh();
                    }
                })
            }
        });
    });

    //REQUEST BOOKS -- END

    //RELEASE BOOKS -- START

    $('#release_branch').val('').trigger('change');

    //Show Release Books Modal
    $('.release_books').on('click', function(){
        modal_close = false;
        $('#release_books_modal').modal('toggle');
        $('#release_books_modal').modal('show');
    });

    $('#release_branch').on('change', function(){
        branch_id = $(this).val();
        
        if(branch_id != null){
            $('#release_book').prop('disabled', false);
            $('#release_book').val('').trigger('change');
            $('#release_books_modal').find("input,textarea").val('').end();
            getBooksPending();
        }
    });

    $('#release_book').on('change', function(){
        book_type = $(this).val();

        if(book_type != null){
            $.ajax({
                url: '/get_release_pending/'+book_type+'/'+branch_id,
                method: 'get',
                dataType: 'json',
                success:function(data){
                    if(data.starting == 0){
                        swal("Hold!", "No Books Available", "error");
                        $('#release_quantity, #release_starting, #release_remarks').prop('disabled', true);
                        $('#release_books_modal').find("input,textarea").val('').end();
                    }else{
                        $('#release_quantity, #release_starting, #release_remarks').prop('disabled', false);
                        $('#release_stocks').val(data.stocks);
                        $('#release_previous_pending').val(data.pending);
                        $('#release_pending').val(data.pending);
                        $('#release_quantity').val('');
                        $('#release_starting, #release_start').val(data.starting);
                    }
                }
            });
        }
    });

    $('#release_quantity').keyup(function(){
        if($(this).val() == ''){
            $('#release_pending').val($('#release_previous_pending').val());
            $('#release_end').val('');
        }else{
            if(parseInt($(this).val()) > parseInt($('#release_previous_pending').val())){
                $(this).val($('#release_previous_pending').val());
            }
            if(parseInt($(this).val()) > parseInt($('#release_stocks').val())){
                $(this).val($('#release_stocks').val());
            }
            $('#release_pending').val(parseInt($('#release_previous_pending').val()) - parseInt($(this).val()));
            $('#release_end').val(parseInt($('#release_start').val()) + (parseInt($(this).val()) - 1));
        }
    });

    $('#release_starting').keyup(function(){
        $('#release_start').val($(this).val());
        if($('#release_quantity').val() != ''){
            $('#release_end').val(parseInt($('#release_start').val()) + (parseInt($('#release_quantity').val()) - 1));
        }
    });

    $(document).on('submit', '#release_books_form', function(e){
        e.preventDefault();

        var input = $('.save_book_release');
        var button = document.getElementsByClassName("save_book_release")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_book_release',
            data: $(this).serialize(),
            method: 'POST',
            dataType: 'text',
            success:function(data){
                if(data){
                    swal("Releasing Stopped", "Conflict! Book #" + data + " already released or lost", "error");                       
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                notif('Success!', 'Books Released!', 'success', 'glyphicon-ok');
                $('#release_books_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('click', '.receive_release', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Book Received?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/received_book_release/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Book Release Pending Again!', '', 'success', 'glyphicon-ok');
                        refresh();
                    }
                })
            }
        });
    });

    $(document).on('click', '.pending_release', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Book Release back to Pending?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/pending_book_release/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Book Received!', '', 'success', 'glyphicon-ok');
                        refresh();
                    }
                })
            }
        });
    });

    $(document).on('click', '.return_release', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Return Book Released?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/return_book_release/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Book Returned!', '', 'success', 'glyphicon-ok');
                        refresh();
                    }
                })
            }
        });
    });

    //Release Books SELECT 2
    $('#release_branch').select2({
        placeholder: 'Select Branch',
        ajax: {
            url: "/get_release_branch",
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
        }
    });

    function getBooksPending(){
        $('#release_book').select2({
            placeholder: 'Select Book',
            ajax: {
                url: "/get_release_books/"+branch_id,
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

    //RELEASE BOOKS -- END

    //ASSIGN BOOKS -- START

    //Show Assign Books Modal
    $('.assign_books').on('click', function(){
        modal_close = false;
        $('#assign_books_modal').modal('toggle');
        $('#assign_books_modal').modal('show');
    });

    $('#assign_student_name').on('change', function(){
        $('#assign_book_type').prop('disabled', false);
        student_id = $(this).val();
        getAvailableBookType();
    });

    $('#assign_book_type').on('change', function(){
        $('#assign_book').prop('disabled', false);
        book_type = $(this).val();
        getAvailableBooks();
    });

    $(document).on('submit', '#assign_books_modal', function(e){
        e.preventDefault();

        var input = $('.save_book_assign');
        var button = document.getElementsByClassName("save_book_assign")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_book_assign',
            data: $('#assign_books_form').serialize(),
            method: 'POST',
            dataType: 'text',
            success:function(data){
                ($('#assign_continuous').is(':checked')) ? assign_modal_clear() : $('#assign_books_modal').modal('hide');
                notif('Success!', 'Book Assigned!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Assign Books SELECT 2
    $('#assign_student_name').select2({
        placeholder: 'Select Student',
        ajax: {
            url: '/get_assign_student',
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

    function getAvailableBookType(){
        $('#assign_book_type').select2({
            placeholder: 'Select Book',
            ajax: {
                url: '/get_available_book_type/'+student_id,
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

    function getAvailableBooks(){
        $('#assign_book').select2({
            placeholder: 'Select Book No.',
            ajax: {
                url: '/get_available_book/'+book_type,
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

    //ASSIGN BOOKS -- END

    //LOST BOOKS -- START

    $(document).on('click', '.lost_book', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Lost Book?',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/lost_book/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Book Lost!', '', 'info', 'glyphicon-info-sign');
                        refresh();
                    }
                })
            }
        });
    });

    //LOST BOOKS -- END

    //RETURN BOOKS -- START

    $(document).on('click', '.return_book', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Return Book?',
            text: 'This book will be available again',
            type: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/return_book/'+id,
                    method: 'get',
                    type: 'json',
                    success:function(data){
                        notif('Success!', 'Book Returned!', 'success', 'glyphicon-ok');
                        refresh();
                    }
                })
            }
        });
    });

    //RETURN BOOKS -- END


    //FUNCTIONS -- END
});