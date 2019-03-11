@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Book Management Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red lost_books">
            <i class="fas fa-eye-slash"></i>
            </button>
            <button class="btn btn-secondary bg-red return_books">
            <i class="fas fa-undo"></i>
            </button>
            <button class="btn btn-secondary bg-red assign_books">
            <i class="fas fa-people-carry"></i>
            </button>
            <button class="btn btn-secondary bg-red release_books">
            <i class="fas fa-external-link-alt"></i>
            </button>
            <button class="btn btn-secondary bg-red request_books">
            <i class="fas fa-hand-holding"></i>
            </button>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a class="books_pick" href="#branch_tab" data-toggle="tab">Branch</a></li>
                    <li><a class="books_pick" href="#student_tab" data-toggle="tab">Student</a></li>
                    <li><a class="books_pick" href="#books_tab" data-toggle="tab">Books</a></li>
                    <li><a class="books_pick" href="#request_tab" data-toggle="tab">Request History</a></li>
                    <li><a class="books_pick" href="#release_tab" data-toggle="tab">Release History</a></li>
                    <li><a class="books_pick" href="#assign_tab" data-toggle="tab">Assign History</a></li>
                    <li><a class="books_pick" href="#return_tab" data-toggle="tab">Return History</a></li>
                    <li><a class="books_pick" href="#lost_tab" data-toggle="tab">Lost History</a></li>
                </ul>

                <div class="tab-content">
                
                    @include('includes.tabs.book_management_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.request_books_modal')
    @include('includes.modals.release_books_modal')
    @include('includes.modals.assign_books_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        var book_type;
        var books_pick;
        var branch_id;
        var student_id;

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

        $('.select2').select2();

        $('#request_book').select2({
            placeholder: "Select Book"
        });

        $('#release_branch').select2({
            placeholder: "Select Branch"
        });

        //INITIALIZE -- END


        //DATATABLES -- START

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        var request_books_table = $('#books_request_table').DataTable({
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
            responsive: true,
            ajax: '/view_request_books',
            columns: [
                {data: 'pending_request.branch.name', name: 'branch'},
                {data: 'pending_request.book_type.name', name: 'book'},
                {data: 'previous_pending', name: 'previous_pending'},
                {data: 'quantity', name: 'quantity'},
                {data: 'pending', name: 'pending'},
                {data: 'created_at', name: 'date'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                { width: 80, targets: 0 }, //branch
                { width: 80, targets: 1 }, //book type
                { width: 70, targets: 2 }, //previous
                { width: 70, targets: 3 }, //quantity
                { width: 70, targets: 4 }, //pending
                { width: 130, targets: 5 }, //date
                { width: 160, targets: 6 }, //remarks
                { width: 110, targets: 7 }, //action
            ],
            order: [[
                5, 'desc'
            ]]
        });

        function refresh_request_books(){
            request_books_table.ajax.reload();
        }

        var release_books_table = $('#books_release_table').DataTable({
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 2
            },
            responsive: true,
            ajax: '/view_release_books',
            columns: [
                {data: 'pending_request.branch.name', name: 'branch'},
                {data: 'pending_request.book_type.name', name: 'book'},
                {data: 'previous_pending', name: 'previous_pending'},
                {data: 'quantity', name: 'quantity'},
                {data: 'pending', name: 'pending'},
                {data: 'book_range', name: 'book_range'},
                {data: 'created_at', name: 'date'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                { width: 80, targets: 0 }, //branch
                { width: 80, targets: 1 }, //book type
                { width: 70, targets: 2 }, //previous
                { width: 70, targets: 3 }, //quantity
                { width: 70, targets: 4 }, //pending
                { width: 110, targets: 5 }, //book range
                { width: 130, targets: 6 }, //date
                { width: 160, targets: 7 }, //remarks
                { width: 110, targets: 8 }, //action
            ],
            order: [[
                6, 'desc'
            ]]
        });

        function refresh_release_books(){
            release_books_table.ajax.reload();
        }

        var assign_books_table = $('#books_assign_table').DataTable({
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: '/view_assign_books',
            columns: [
                {data: 'student_name', name: 'name'},
                {data: 'student.branch.name', name: 'branch'},
                {data: 'books.book_type.name', name: 'book'},
                {data: 'books.name', name: 'book_no'},
                {data: 'created_at', name: 'date'},
            ],
            columnDefs: [
                { width: 250, targets: 0 }, //student name
                { width: 150, targets: 1 }, //branch
                { width: 150, targets: 2 }, //book
                { width: 100, targets: 3 }, //book no.
                { width: 200, targets: 4 }, //date
            ],
            order: [[
                4, 'desc'
            ]]
        });

        function refresh_assign_books(){
            assign_books_table.ajax.reload();
        }

        //DATATABLES -- END


        //FUNCTIONS -- START

        $('.books_pick').on('click', function(){
            books_pick = $(this).text();
            if(books_pick == 'Request History'){
                refresh_request_books()
            }
        })

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
                    refresh_request_books();
                },
                error: function(data){
                    swal("Oh no!", "Something went wrong, try again.", "error");
                    button.disabled = false;
                    input.html('SAVE CHANGES');
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
                    swal('Success!', 'Record has been saved to the Database!', 'success');
                    $('#release_books_modal').modal('hide');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    refresh_release_books();
                },
                error: function(data){
                    swal("Oh no!", "Something went wrong, try again.", "error");
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                }
            });
        });

        //Release Books SELECT 2
        $('#release_branch').select2({
            placeholder: 'Select Branch',
            ajax: {
                url: "/get_release_branch",
                dataType: 'json',
                
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
                    refresh_assign_books();
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
                url: '/get_student',
                dataType: 'json',
                
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
                placeholder: 'Select Book',
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


        //FUNCTIONS -- END
    });
</script>

@endsection