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
                    <li class="active"><a class="" href="#branch_tab" data-toggle="tab">Branch</a></li>
                    <li><a class="" href="#student_tab" data-toggle="tab">Student</a></li>
                    <li><a class="" href="#books_tab" data-toggle="tab">Books</a></li>
                    <li><a class="" href="#request_tab" data-toggle="tab">Request History</a></li>
                    <li><a class="" href="#release_tab" data-toggle="tab">Release History</a></li>
                    <li><a class="" href="#assign_tab" data-toggle="tab">Assign History</a></li>
                    <li><a class="" href="#return_tab" data-toggle="tab">Return History</a></li>
                    <li><a class="" href="#lost_tab" data-toggle="tab">Lost History</a></li>
                </ul>

                <div class="tab-content">
                
                    @include('includes.tabs.book_management_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.request_books_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        var book_type;

        $('#request_books_modal').on('hidden.bs.modal', function(e){
            $(this).find("input,textarea,select").val('').end().trigger('change');
            $('#request_book').val('').trigger('change');
            $('#request_quantity, #request_remarks').prop('disabled', true);
        });

        $('.select2').select2();

        $('#request_book').select2({
            placeholder: "Select Book"
        });

        //INITIALIZE -- END


        //DATATABLES -- START


        //DATATABLES -- END


        //FUNCTIONS -- START

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
                        $('#request_quantity').prop('disabled', false);
                        $('#request_quantity').val('');
                        $('#request_remarks').prop('disabled', false);
                    }
                });
            }
        });

        $('#request_quantity').keyup(function(){
            $('#request_pending').val(parseInt($('#request_previous_pending').val()) + parseInt($('#request_quantity').val()));
        });

        $('#save_book_request').on('click', function(){
            
        });

        //REQUEST BOOKS -- END


        //FUNCTIONS -- END
    });
</script>

@endsection