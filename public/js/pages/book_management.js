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


    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('#request_books_modal').on('hidden.bs.modal', function(e){
        $(this).find("input,textarea,select").val('').end();
        $('#request_book').val('').trigger('change');
        $('#request_quantity, #request_remarks').prop('disabled', true);
    });

    $('#release_books_modal').on('hidden.bs.modal', function(e){
        $(this).find("input,textarea,select").val('').end();
        $('#release_book, #release_branch').val('').trigger('change');
        $('#release_quantity, #release_book, #release_starting, #release_remarks').prop('disabled', true);
    });

    $('#assign_books_modal').on('hidden.bs.modal', function(e){
        $(this).find("input,textarea,select").val('').end();
        $('#assign_student_name, #assign_book_type, #assign_book').val('').trigger('change');
        $('#assign_book_type, #assign_book').prop('disabled', true);
    });

    $('.select2').select2();

    $('#request_book').select2({
        placeholder: "Select Book"
    });

    $('#release_branch').select2({
        placeholder: "Select Branch"
    });

    $('.books_pick').on('click', function(){
        current_tab = $(this).text();
        pickRefresh();
    })

    function pickRefresh(){
        if(current_tab != 'Branch' && current_tab != 'Student' && current_tab != 'SSV' ){
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

        if(current_tab != 'Student' && current_tab != 'SSV' &&
            current_tab != 'Request History' && current_tab != 'Release History'){
            $('.invoice_select').show();
            $('#invoice_select').next(".select2-container").show();
        }
        else{
            $('.invoice_select').hide();
            $('#invoice_select').next(".select2-container").hide();
        }

        if(current_tab != 'Student' && current_tab != current_tab != 'SSV'){
            $('.student_status_select').hide();
            $('#student_status_select').next(".select2-container").hide();
            $('.program_select').hide();
            $('#program_select').next(".select2-container").hide();
        }

        if(current_tab == 'Branch'){
            $('.status_select').show();
            $('#status_select').next(".select2-container").show();

            $('.branch_select').hide();
            $('#branch_select').next(".select2-container").hide();

            refresh_books_branch_table();
        }
        else if(current_tab == 'Student'){
            $('.student_status_select').show();
            $('#student_status_select').next(".select2-container").show();
            $('.program_select').show();
            $('#program_select').next(".select2-container").show();

            refresh_books_student_table();
        }
        else if(current_tab == 'SSV'){
            $('.student_status_select').show();
            $('#student_status_select').next(".select2-container").show();
            $('.program_select').show();
            $('#program_select').next(".select2-container").show();

            refresh_books_ssv_student_table();
        }
        else if(current_tab == 'Books'){
            refresh_books();
        }
        else if(current_tab == 'Request History'){
            refresh_request_books();
        }
        else if(current_tab == 'Release History'){
            refresh_release_books();
        }
        else if(current_tab == 'Assign History'){
            refresh_assign_books();
        }
        else if(current_tab == 'Return History'){
            refresh_return_books()
        }
        else if(current_tab == 'Lost History'){
            refresh_lost_books()
        }
    }

    //INITIALIZE -- END


    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
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
            columnDefs: [
                { width: 30, targets: 0 }, //id
                { width: 80, targets: 1 }, //branch
                { width: 180, targets: 2 }, //book type
                { width: 70, targets: 3 }, //previous
                { width: 70, targets: 4 }, //quantity
                { width: 70, targets: 5 }, //pending
                { width: 130, targets: 6 }, //date
                { width: 50, targets: 7 }, //status
                { width: 160, targets: 8 }, //remarks
                { width: 100, targets: 9 }, //action
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
            columnDefs: [
                { width: 30, targets: 0 }, //id
                { width: 80, targets: 1 }, //branch
                { width: 180, targets: 2 }, //book type
                { width: 70, targets: 3 }, //previous
                { width: 70, targets: 4 }, //quantity
                { width: 70, targets: 5 }, //pending
                { width: 130, targets: 6 }, //book range
                { width: 130, targets: 7 }, //date
                { width: 50, targets: 8 }, //status
                { width: 160, targets: 9 }, //remarks
                { width: 110, targets: 10 }, //action
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
            columns: [
                {data: 'student_name', name: 'name'},
                {data: 'student.branch.name', name: 'branch'},
                {data: 'books.book_type.description', name: 'book'},
                {data: 'books.name', name: 'book_no'},
                {data: 'books.reference_no.invoice_ref_no', name: 'reference_no'},
                {data: 'created_at', name: 'date'},
            ],
            columnDefs: [
                { width: 250, targets: 0 }, //student name
                { width: 80, targets: 1 }, //branch
                { width: 150, targets: 2 }, //book
                { width: 100, targets: 3 }, //book no.
                { width: 130, targets: 4 }, //reference no.
                { width: 200, targets: 5 }, //date
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
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
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
                {data: 'reference_no.lead_ref_no', name: 'lead_ref_no'},
                {data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'branch.name', name: 'branch'},
                {data: 'status', name: 'status'},
                {data: 'student_name', name: 'student_name'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                { width: 180, targets: 0 }, //book type
                { width: 80, targets: 1 }, //book no.
                { width: 130, targets: 2 }, //lead ref no
                { width: 130, targets: 3 }, //invoice ref no
                { width: 100, targets: 4 }, //branch
                { width: 100, targets: 5 }, //status
                { width: 250, targets: 6 }, //student name
                { width: 100, targets: 7 }, //action
            ]
        });
    }

    function refresh_books_student_table(){
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
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            buttons: [
                {extend: 'print', title: 'LEAD System', orientation: 'landscape', pageSize: 'FOLIO'},
                {extend: 'pdfHtml5', title: 'LEAD System', orientation: 'portrait', pageSize: 'FOLIO'},
            ],
            responsive: true,
            ajax: {
                url: '/view_student_books',
                data:{ 
                    student_status_select: student_status_select,
                    program_select: program_select,
                    branch_select: branch_select,
                }
            },
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
            columnDefs: [
                { width: 250, targets: 0 }, //student name
                { width: 100, targets: 1 }, //branch
                { width: 60, targets: 2 }, //book 1
                { width: 60, targets: 3 }, //wb 1
                { width: 60, targets: 4 }, //book 2
                { width: 60, targets: 5 }, //wb 2
                { width: 60, targets: 6 }, //kanji
                { width: 150, targets: 7 }, //program
                { width: 110, targets: 8 }, //status
                { width: 110, targets: 9 }, //departure
            ],
            order: [[5, 'asc']]
        });
    }

    function refresh_books_ssv_student_table(){
        var books_ssv_student_table = $('#books_ssv_student_table').DataTable({
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
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            buttons: [
                {extend: 'print', title: 'LEAD System', orientation: 'landscape', pageSize: 'FOLIO'},
                {extend: 'pdfHtml5', title: 'LEAD System', orientation: 'portrait', pageSize: 'FOLIO'},
            ],
            responsive: true,
            ajax: {
                url: '/view_ssv_student_books',
                data:{ 
                    student_status_select: student_status_select,
                    program_select: program_select,
                    branch_select: branch_select,
                }
            },
            columns: [
                {data: 'student_name', name: 'student_name'},
                {data: 'branch.name', name: 'branch'},
                {data: 'book_1_ssv', name: 'book_1_ssv'},
                {data: 'wb_1_ssv', name: 'wb_1_ssv'},
                {data: 'book_2_ssv', name: 'book_2_ssv'},
                {data: 'wb_2_ssv', name: 'wb_2_ssv'},
                {data: 'kanji_ssv', name: 'kanji_ssv'},
                {data: 'program.name', name: 'program', defaultContent: ''},
                {data: 'status', name: 'status'},
            ],
            columnDefs: [
                { width: 250, targets: 0 }, //student name
                { width: 100, targets: 1 }, //branch
                { width: 90, targets: 2 }, //book 1 ssv
                { width: 90, targets: 3 }, //wb 1 ssv
                { width: 90, targets: 4 }, //book 2 ssv
                { width: 90, targets: 5 }, //wb 2 ssv
                { width: 90, targets: 6 }, //kanji ssv
                { width: 150, targets: 7 }, //program
                { width: 110, targets: 8 }, //status
            ]
        });
    }

    function refresh_books_branch_table(){
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
            dom: 'Bflrtip',
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            buttons: [
                {extend: 'print', title: 'LEAD System - '+book_status+' Books' },
                {extend: 'pdfHtml5', title: 'LEAD System - '+book_status+' Books' },
            ],
            responsive: true,
            ajax: {
                url: '/view_branch_books',
                data: {
                    book_status: book_status,
                    invoice_select: invoice_select,
                }
            },
            columns: [
                {data: 'name', name: 'branch'},
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
            ],
            columnDefs: [
                { width: 70, targets: 0 }, //branch
                { width: 90, targets: 1 }, //book 1
                { width: 90, targets: 2 }, //wb 1
                { width: 90, targets: 3 }, //book 2
                { width: 90, targets: 4 }, //wb 2
                { width: 90, targets: 5 }, //kanji
                { width: 90, targets: 6 }, //book 1 ssv
                { width: 90, targets: 7 }, //wb 1 ssv
                { width: 90, targets: 8 }, //book 2 ssv
                { width: 90, targets: 9 }, //wb 2 ssv
                { width: 90, targets: 10 }, //kanji ssv
            ]
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
            //dom: 'Bflrtip',
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
            columns: [
                {data: 'books.book_type.description', name: 'book'},
                {data: 'books.name', name: 'book_no'},
                {data: 'books.reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'books.branch.name', name: 'branch'},
                {data: 'stud_id', name: 'student_name'},
                {data: 'created_at', name: 'date'},
            ],
            columnDefs: [
                { width: 180, targets: 0 }, //book type
                { width: 130, targets: 1 }, //book no
                { width: 130, targets: 2 }, //invoice ref no
                { width: 70, targets: 3 }, //branch
                { width: 250, targets: 4 }, //student name
                { width: 130, targets: 5 }, //date
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
            columns: [
                {data: 'books.book_type.description', name: 'book'},
                {data: 'books.name', name: 'book_no'},
                {data: 'books.reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                {data: 'books.branch.name', name: 'branch'},
                {data: 'stud_id', name: 'student_name'},
                {data: 'created_at', name: 'date'},
            ],
            columnDefs: [
                { width: 180, targets: 0 }, //book type
                { width: 130, targets: 1 }, //book no
                { width: 130, targets: 2 }, //invoice ref no
                { width: 70, targets: 3 }, //branch
                { width: 250, targets: 4 }, //student name
                { width: 130, targets: 5 }, //date
            ],
            order: [[
                5, 'desc'
            ]]
        });
    }

    pickRefresh();
    //DATATABLES -- END


    //FUNCTIONS -- START

    $('#status_select').on('change', function(){
        book_status = $(this).val();
        refresh_books_branch_table();
    });

    $('#book_type_select').on('change', function(){
        book_type_select = $(this).val();
        pickRefresh();
    });

    $('#student_status_select').on('change', function(){
        student_status_select = $(this).val();
        pickRefresh();
    });

    $('#program_select').on('change', function(){
        program_select = $(this).val();
        pickRefresh();
    });

    $('#branch_select').on('change', function(){
        branch_select = $(this).val();
        pickRefresh();
    });
    
    $('#invoice_select').on('change', function(){
        invoice_select = $(this).val();
        pickRefresh();
    });

    //REQUEST BOOKS -- START

    //Open Request Books Modal
    $('.request_books').on('click', function(){
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

    $('.save_book_request').on('click', function(e){
        e.preventDefault();

        var input = $(this);
        var button = this;

        button.disabled = true;
        input.html('SAVING...');

        book_type = $('#request_book').val();

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_book_request',
            data: $('#request_books_form').serialize(),
            method: 'POST',
            dataType: 'text',
            success: function(data){
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#request_books_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                pickRefresh();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
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
                        swal('Book Request Approved!', '', 'success');
                        pickRefresh();
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
                        swal('Book Request Delivered!', '', 'success');
                        pickRefresh();
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
                        swal('Book Request Pending Again!', '', 'info');
                        pickRefresh();
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
                        swal('Book Request Cancelled!', '', 'info');
                        pickRefresh();
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

    $('.save_book_release').on('click', function(e){
        e.preventDefault();

        var input = $(this);
        var button = this;

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_book_release',
            data: $('#release_books_form').serialize(),
            method: 'POST',
            dataType: 'text',
            success:function(data){
                if(data){
                    swal("Releasing Stopped", "Conflict! Book #" + data + " already released or lost", "error");                       
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    return;
                }
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#release_books_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                pickRefresh();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
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
                        swal('Book Release Pending Again!', '', 'success');
                        pickRefresh();
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
                        swal('Book Received!', '', 'success');
                        pickRefresh();
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
                        swal('Book Returned!', '', 'success');
                        pickRefresh();
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

    $('.save_book_assign').on('click', function(e){
        e.preventDefault();

        var input = $(this);
        var button = this;

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
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#assign_books_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                pickRefresh();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
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
                        swal('Book Lost!', 'success');
                        pickRefresh();
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
                        swal('Book Returned!', 'success');
                        pickRefresh();
                    }
                })
            }
        });
    });

    //RETURN BOOKS -- END


    //FUNCTIONS -- END
});