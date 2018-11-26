@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        School Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_school" data-toggle="modal" data-target="#school_modal">
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
                    <div class="tab-pane fade in active" id="schools">
                        <table id="schools_table" class="table table-hover table-striped table-bordered" cellspacing="0">
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
    
    @include('includes.modals.school_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START
        
        $('.datepicker').datepicker();

        $('.select2').select2()

        function refresh_school_table(){
            schools_table.ajax.reload(); //reload datatable ajax
        }

        $('#school_modal').on('shown.bs.modal', function(){
            $('#school_name').focus();
        });

        $("#school_modal").on("hidden.bs.modal", function(e){
            $('#add_school_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
        });

        //INITIALIZE -- END

        //VALIDATION -- START

        function validate_school(){
            var name = $('#school_name').val();
            if(/\S/.test(name)){
                return true;
            }
            else{
                return false;
            }
        }

        //VALIDATION -- END

        //DATATABLES -- START

        var schools_table = $('#schools_table').DataTable({
            processing: true,
            ajax: '/schoolsView',
            scrollX: true,
            autoWidth: true,
            columns: [
                {data: 'name', name: 'name'},
                {data: "action", orderable:false,searchable:false}
            ]
        });

        //Add or Edit School
        $('.save_school').on('click', function(e){
            e.preventDefault();
            
            var input = $(this);
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            var validated = validate_school();
            
            if(validated){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'text',
                    method: 'POST',
                    url: '/save_school',
                    data: $('#add_school_form').serialize(),
                    success: function(data){
                        swal('Success!', 'Record has been saved to the Database!', 'success');
                        $('#school_modal').modal('hide');
                        button.disabled = false;
                        input.html('SAVE CHANGES');
                        refresh_school_table();
                    },
                    error: function(data){
                        swal("Oh no!", "Something went wrong, try again.", "error");
                        button.disabled = false;
                        input.html('SAVE CHANGES');
                    }
                });
            }
            else{
                $('#add_school_form :input.required').each(function (){
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

        //Open School Modal (ADD)
        $('.add_school').on('click', function(){
            $('#add_edit').val('add');
        });

        //Open School Modal (EDIT)
        $(document).on('click', '.edit_school', function(){
            var id = $(this).attr('id');

            $.ajax({
                url: '/get_school',
                method: 'get',
                data: {id: id},
                dataType: 'json',
                success:function(data){
                    $('#add_edit').val('edit');
                    $('#id').val(data.id);
                    $('#school_name').val(data.name);
                    $('#school_modal').modal('toggle');
                    $('#school_modal').modal('show');
                }
            });
        });

        //Delete School
        $(document).on('click', '.delete_school', function(){
            var id = $(this).attr('id');
            console.log(id);

            swal({
                title: 'Are you sure?',
                text: 'You are about to delete a school. This may affect multiple rows',
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
                        url: '/delete_school',
                        method: 'get',
                        data: {id:id},
                        type: 'json',
                        success:function(data){
                            swal('Deleted!', 'This School has been Deleted', 'success');
                            refresh_school_table();
                        }
                    })
                }
            });

            
        });
        
        //FUNCTIONS -- END
    });
</script>

@endsection