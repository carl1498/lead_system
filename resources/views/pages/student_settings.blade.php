@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Settings Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_student_settings">
                <i class="fa fa-plus-square"></i>
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
                    <li class="active"><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Program</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">School</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Benefactor</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Year</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Month</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Course</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.tabs.student_settings_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.student_settings_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        var current_settings = 'Program';
        var student_settings;

        $('#student_settings_modal').on('shown.bs.modal', function(){
            $('#student_settings_name').focus();
        });

        $("#student_settings_modal").on("hidden.bs.modal", function(e){
            $('#student_settings_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
        });

        $('body').tooltip({
            selector: '[data-toggle="tooltip"]',
            trigger : 'hover'
        });
        
        $('input, select').attr('autocomplete', 'off');

        //INITIALIZE -- END


        //DATATABLES -- START

        function refresh_student_settings(){
            student_settings = $('#student_settings').DataTable({
                processing: true,
                destroy: true,
                autoWidth: true,
                scrollCollapse: true,
                ajax: {
                    url: '/view_student_settings/{current_settings}',
                    data: {current_settings: current_settings}
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'action', orderable: false, searchable: false}
                ]
            });
        }

        refresh_student_settings();

        //DATATABLES -- END

        //FUNCTIONS -- START

        //Clicking on tabs
        $('.settings_pick').on('click', function(){
            current_settings = $(this).text();

            refresh_student_settings();
        });

        //save student settings
        $('.save_student_settings').on('click', function(e){
            e.preventDefault();

            var input = $(this);
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'json', 
                method: 'POST',
                url: '/save_student_settings',
                data: $('#student_settings_form').serialize() + '&current_settings=' + current_settings,
                success: function(data){
                    swal('Success!', 'Record has been saved to the Database!', 'success');
                    $('#student_settings_modal').modal('hide');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    refresh_student_settings();
                },
                error: function(data){
                    swal("Oh no!", "Something went wrong, try again.", "error");
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                }
            });
        });

        //Open Student Settings Modal (ADD)
        $('.add_student_settings').on('click', function(){
            $('#add_edit').val('add');
            $('#student_settings_modal .modal-title').text('Add ' + current_settings);
            $('#student_settings_form label').text(current_settings + ' Name');
            $('#student_settings_name').attr('placeholder', 'Enter ' + current_settings + ' Name');
            
            $('#student_settings_modal').modal('toggle');
            $('#student_settings_modal').modal('show');
        });

        //Open Student Settings Modal (EDIT)
        $(document).on('click', '.edit_student_settings', function(){
            var id = $(this).attr('id');

            $.ajax({
                url: '/get_student_settings/{id}/{current_settings}',
                method: 'get',
                data: {
                    id: id,
                    current_settings: current_settings
                },
                dataType: 'text',
                success: function(data){
                    $('#id').val(id);
                    $('#add_edit').val('edit');

                    $('#student_settings_name').val(data);
                    
                    $('#student_settings_modal .modal-title').text('Add ' + current_settings);
                    $('#student_settings_form label').text(current_settings + ' Name');
                    $('#student_settings_modal').modal('toggle');
                    $('#student_settings_modal').modal('show');
                }
            });
        });

        //Delete Student Settings
        $(document).on('click', '.delete_student_settings', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Are you sure?',
                text: 'You are about to delete this setting. This may affect multiple rows',
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
                        url: '/delete_student_settings',
                        method: 'get',
                        data: {
                            id:id,
                            current_settings: current_settings
                        },
                        type: 'json',
                        success:function(data){
                            swal('Deleted!', 'This Student Setting has been Deleted', 'success');
                            refresh_student_settings();
                        }
                    })
                }
            });
        });

        //FUNCTIONS -- END
    });
</script>

@endsection