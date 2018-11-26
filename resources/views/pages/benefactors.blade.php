@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Program Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_program" data-toggle="modal" data-target="#program_modal">
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
                    <div class="tab-pane fade in active" id="programs">
                        <table id="programs_table" class="table table-hover table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="width: 100%">Name</th>
                                    <th style="width: 90px">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.program_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START
        
        $('.datepicker').datepicker();

        $('.select2').select2()

        function refresh_program_table(){
            programs_table.ajax.reload(); //reload datatable ajax
        }

        $('#program_modal').on('shown.bs.modal', function(){
            $('#program_name').focus();
        });

        $("#program_modal").on("hidden.bs.modal", function(e){
            $('#program_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
        });

        //INITIALIZE -- END

        //VALIDATION -- START

        function validate_program(){
            var name = $('#program_name').val();
            if(/\S/.test(name)){
                return true;
            }
            else{
                return false;
            }
        }

        //VALIDATION -- END

        //DATATABLES -- START

        var programs_table = $('#programs_table').DataTable({
            processing: true,
            ajax: '/programsView',
            scrollX: true,
            autoWidth: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: "action", orderable:false,searchable:false}
            ]
        });

        //Add or Edit Program
        $('.save_program').on('click', function(e){
            e.preventDefault();
            
            var input = $(this);
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            var validated = validate_program();
            
            if(validated){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'text',
                    method: 'POST',
                    url: '/save_program',
                    data: $('#program_form').serialize(),
                    success: function(data){
                        swal('Success!', 'Record has been saved to the Database!', 'success');
                        $('#program_modal').modal('hide');
                        button.disabled = false;
                        input.html('SAVE CHANGES');
                        refresh_program_table();
                    },
                    error: function(data){
                        swal("Oh no!", "Something went wrong, try again.", "error");
                        button.disabled = false;
                        input.html('SAVE CHANGES');
                    }
                });
            }
            else{
                $('#program_form :input.required').each(function (){
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

        //FUNCTIONS -- START

        //Open Program Modal (ADD)
        $('.add_program').on('click', function(){
            $('#add_edit').val('add');
        });

        //Open Program Modal (EDIT)
        $(document).on('click', '.edit_program', function(){
            var id = $(this).attr('id');

            $.ajax({
                url: '/get_program',
                method: 'get',
                data: {id: id},
                dataType: 'json',
                success:function(data){
                    $('#add_edit').val('edit');
                    $('#id').val(data.id);
                    $('#program_name').val(data.name);
                    $('#program_modal').modal('toggle');
                    $('#program_modal').modal('show');
                }
            });
        });

        //Delete Program
        $(document).on('click', '.delete_program', function(){
            var id = $(this).attr('id');
            console.log(id);

            swal({
                title: 'Are you sure?',
                text: 'You are about to delete a program. This may affect multiple rows',
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
                        url: '/delete_program',
                        method: 'get',
                        data: {id:id},
                        type: 'json',
                        success:function(data){
                            swal('Deleted!', 'This Program has been Deleted', 'success');
                            refresh_program_table();
                        }
                    })
                }
            });

            
        });
        
        //FUNCTIONS -- END
    });
</script>

@endsection