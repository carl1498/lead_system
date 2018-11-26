@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Benefactor Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_benefactor" data-toggle="modal" data-target="#benefactor_modal">
                <i class="fa fa-plus-square"></i>
            </button>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-body">
                    <div class="tab-pane fade in active" id="benefactors">
                        <table id="benefactors_table" class="table table-hover table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 100%; max-width: 100px;">Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.benefactor_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START
        
        $('.datepicker').datepicker();

        $('.select2').select2()

        function refresh_benefactor_table(){
            benefactors_table.ajax.reload(); //reload datatable ajax
        }

        $('#benefactor_modal').on('shown.bs.modal', function(){
            $('#benefactor_name').focus();
            $(this).find("input,textarea,select").val('').end();
        });

        $("#benefactor_modal").on("hidden.bs.modal", function(e){
            $('#add_benefactor_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
        });

        //INITIALIZE -- END

        //VALIDATION -- START

        function validate_benefactor(){
            var name = $('#benefactor_name').val();
            if(/\S/.test(name)){
                return true;
            }
            else{
                return false;
            }
        }

        //VALIDATION -- END

        //DATATABLES -- START

        var benefactors_table = $('#benefactors_table').DataTable({
            processing: true,
            ajax: '/benefactorsView',
            scrollX: true,
            autoWidth: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: "action", orderable:false,searchable:false}
            ]
        });
        
        $('.add_benefactor_button').on('click', function(e){
            e.preventDefault();
            
            var input = $(this);
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            var validated = validate_benefactor();
            
            if(validated){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'text',
                    method: 'POST',
                    url: '/add_benefactor',
                    data: $('#add_benefactor_form').serialize(),
                    success: function(data){
                        swal('Success!', 'Record has been added to the Database!', 'success');
                        $('#benefactor_modal').modal('hide');
                        button.disabled = false;
                        input.html('SAVE CHANGES');
                        refresh_benefactor_table();
                    },
                    error: function(data){
                        swal("Oh no!", "Something went wrong, try again.", "error")
                        button.disabled = false;
                        input.html('SAVE CHANGES');
                    }
                });
            }
            else{
                $('#add_benefactor_form :input.required').each(function (){
                    if(!/\S/.test($(this).val())){
                        this.style.setProperty('border-color', 'red', 'important');
                    }
                });
                swal("Error!", "Invalid Input, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });

        //DATATABLES -- END
    });
</script>

@endsection