@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Invoice Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_books">
            <i class="fas fa-book-medical"></i>
            </button>
            <button class="btn btn-secondary bg-red add_invoice">
            <i class="fas fa-file-medical"></i>
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
                    <li class="active"><a class="invoice_pick" href="#invoice_tab" data-toggle="tab">Invoice</a></li>
                    <li><a class="invoice_pick" href="#add_books_tab" data-toggle="tab">Add Book History</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.invoice_select')
                    @include('includes.tabs.invoice_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    
    @include('includes.modals.invoice_modal')
    @include('includes.modals.add_books_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
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
                    leftColumns: 2
                },
                responsive: true,
                ajax: {
                    url: '/view_invoice/{invoice_select}',
                    data: {invoice_select: invoice_select}
                },
                columns: [
                    {data: 'reference_no.lead_ref_no', name: 'lead_ref_no'},
                    {data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                    {data: 'book_1', name: 'book_1'},
                    {data: 'wb_1', name: 'wb_1'},
                    {data: 'book_2', name: 'book_2'},
                    {data: 'wb_2', name: 'wb_2'},
                    {data: 'kanji', name: 'kanji'},
                    {data: 'created_at', name: 'date'},
                    {data: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [
                    { width: 120, targets: 0 }, //lead ref no
                    { width: 120, targets: 1 }, //invoice ref no
                    { width: 60, targets: 2 }, //book 1
                    { width: 60, targets: 3 }, //wb 1
                    { width: 60, targets: 4 }, //book 2
                    { width: 60, targets: 5 }, //wb 2
                    { width: 60, targets: 6 }, //kanji
                    { width: 130, targets: 7 }, //date
                    { width: 80, targets: 8 }, //action
                ],
                order: [[
                    7, 'desc'
                ]]
            });
        }

        function refresh_add_book_history(){

            var add_books_table = $('#add_books_table').DataTable({
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
                    {data: 'reference_no.lead_ref_no', name: 'lead_ref_no'},
                    {data: 'reference_no.invoice_ref_no', name: 'invoice_ref_no'},
                    {data: 'book_type.name', name: 'book_type'},
                    {data: 'previous_pending', name: 'previous_pending'},
                    {data: 'quantity', name: 'quantity'},
                    {data: 'pending', name: 'pending'},
                    {data: 'book_range', name: 'book_range'},
                    {data: 'created_at', name: 'date'},
                    {data: 'remarks', name: 'remarks'},
                    {data: 'action', orderable: false, searchable: false}
                ],
                columnDefs: [
                    { width: 120, targets: 0 }, //lead ref no
                    { width: 120, targets: 1 }, //invoice ref no
                    { width: 80, targets: 2 }, //book type
                    { width: 60, targets: 3 }, //previous pending
                    { width: 60, targets: 4 }, //quantity
                    { width: 60, targets: 5 }, //pending
                    { width: 120, targets: 6 }, //book no range
                    { width: 130, targets: 7 }, //date
                    { width: 200, targets: 8 }, //remarks
                    { width: 80, targets: 9 }, //action
                ],
                order: [[
                    7, 'desc'
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

        $('.save_books').on('click', function(e){
            e.preventDefault();

            var input = $(this);
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/save_books',
                method: 'POST',
                data: $('#add_books_form').serialize(),
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
        $('.save_invoice').on('click', function(e){
            e.preventDefault();

            var input = $(this);
            
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/save_invoice',
                method: 'POST',
                data: $('#invoice_form').serialize(),
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
            console.log('mao ni');

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
        });

        //INVOICE -- END



        //FUNCTIONS -- END
    });
</script>

@endsection