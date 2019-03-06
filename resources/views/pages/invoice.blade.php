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
                    <li class="active"><a class="settings_pick" href="#invoice_tab" data-toggle="tab">Invoice</a></li>
                    <li><a class="settings_pick" href="#invoice_tab2" data-toggle="tab">Add Book History</a></li>
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

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        var invoice_select = $('#invoice_select').val();

        $("#invoice_modal").on("hidden.bs.modal", function(e){
            $('#invoice_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
        });

        $('.select2').select2();

        $('input, select').attr('autocomplete', 'off');

        //INITIALIZE -- END


        //DATATABLES -- START

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
                    { width: 120, targets: 0 },
                    { width: 120, targets: 1 },
                    { width: 60, targets: 2 },
                    { width: 60, targets: 3 },
                    { width: 60, targets: 4 },
                    { width: 60, targets: 5 },
                    { width: 60, targets: 6 },
                    { width: 130, targets: 7 },
                    { width: 80, targets: 8 },
                ],
                order: [[
                    7, 'desc'
                ]]
            });
        }

        refresh_invoice();

        //DATATABLES -- END


        //FUNCTIONS -- START

        $(document).on('change', '#invoice_select', function(){
            refresh_invoice();
        });

        //Open Add Invoice Modal
        $('.add_invoice').on('click', function(){            
            $('#invoice_modal').modal('toggle');
            $('#invoice_modal').modal('show');
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

        //FUNCTIONS -- END
    });
</script>

@endsection