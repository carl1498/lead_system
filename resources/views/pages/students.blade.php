@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_student" data-toggle="modal" data-target="#student_modal">
                <i class="fa fa-plus-square"></i>
            </button>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="./img/avatar5.png" alt="User profile picture">

                    <h3 class="profile-username text-center">{{ onLoadName() }}</h3>

                    <p class="text-muted text-center">{{ onLoadPosition() }}</p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Followers</b> <a class="pull-right">1,322</a>
                        </li>
                        <li class="list-group-item">
                            <b>Following</b> <a class="pull-right">543</a>
                        </li>
                        <li class="list-group-item">
                            <b>Friends</b> <a class="pull-right">13,287</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Makati</a></li>
                    <li><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Naga</a></li>
                    <li><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Cebu</a></li>
                    <li><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Davao</a></li>
                    <li><a class="status_pick" href="#students_status_tab" data-toggle="tab">Final School</a></li>
                    <li><a class="status_pick" href="#students_status_tab" data-toggle="tab">Back Out</a></li>
                </ul>

                <div class="tab-content">

                    @include('includes.tabs.student_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.student_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        var current_branch = 'Makati';
        var current_status = '';
        
        $('.datepicker').datepicker();

        $('.select2').select2();

        function refresh_tables(){//reload datatable ajax
            students_branch.ajax.reload();
        }

        $('#student_modal').on('shown.bs.modal', function(){
            $('#fname').focus();
        });

        $("#student_modal").on("hidden.bs.modal", function(e){
            $('#student_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
            $('.select2').trigger('change');
        });

        //INITIALIZE -- END


        //DATATABLES -- START

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });

        var columns_students = [
            {data: 'name', name: 'name'},
            {data: 'contact', name: 'contact'},
            {data: 'program.name', name: 'program'},
            {data: 'school.name', name: 'school'},
            {data: 'benefactor.name', name: 'benefactor'},
            {data: 'gender', name: 'gender'},
            {data: 'age', name: 'age'},
            {data: 'course', name: 'course'},
            {data: 'date_of_signup', name: 'date_of_signup'},
            {data: 'referral.fname', name: 'referral'},
            {data: "action", orderable:false,searchable:false}
        ]

        var columnDefs_students = [
            { width: 220, targets: 0 },
            { width: 90, targets: 1 },
            { width: 130, targets: 2 },
            { width: 130, targets: 3 },
            { width: 130, targets: 4 },
            { width: 60, targets: 5 },
            { width: 45, targets: 6 },
            { width: 200, targets: 7 },
            { width: 120, targets: 8 },
            { width: 120, targets: 9 },
            { width: 150, targets: 10 },
            {defaultContent: "",
             targets: "_all"}
        ]

        var columns_students_status = [
            {data: 'name', name: 'name'},
            {data: 'branch.name', name: 'branch'},
            {data: 'contact', name: 'contact'},
            {data: 'program.name', name: 'program'},
            {data: 'school.name', name: 'school'},
            {data: 'benefactor.name', name: 'benefactor'},
            {data: 'gender', name: 'gender'},
            {data: 'age', name: 'age'},
            {data: 'course', name: 'course'},
            {data: 'date_of_signup', name: 'date_of_signup'},
            {data: 'referral.fname', name: 'referral'},
            {data: "action", orderable:false,searchable:false}
        ]
        
        var columnDefs_students_status = [
            { width: 220, targets: 0 },
            { width: 70, targets: 1 },
            { width: 90, targets: 2 },
            { width: 130, targets: 3 },
            { width: 130, targets: 4 },
            { width: 130, targets: 5 },
            { width: 60, targets: 6 },
            { width: 45, targets: 7 },
            { width: 200, targets: 8 },
            { width: 120, targets: 9 },
            { width: 120, targets: 10 },
            { width: 150, targets: 11 },
            {defaultContent: "",
             targets: "_all"}
        ]

        function refresh_student_branch(){
            var students_branch = $('#students_branch').DataTable({
                destroy: true,
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: true,
                ajax: {
                    url: '/student_branch',
                    data: {current_branch: current_branch}
                },
                columnDefs: columnDefs_students,
                columns: columns_students,
            });
        }

        function refresh_student_status(){
            var students_status = $('#students_status').DataTable({
                destroy: true,
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: true,
                responsive: true,
                ajax: {
                    url: '/student_status',
                    data: {current_status: current_status}
                },
                columnDefs: columnDefs_students_status,
                columns: columns_students_status,
            });
        }

        refresh_student_branch();
        refresh_student_status();

        //DATATABLES -- END

        //FUNCTIONS -- START

        $('.branch_pick').on('click', function(){
            current_branch = $(this).text();
            
            refresh_student_branch();
        });

        $('.status_pick').on('click', function(){
            current_status = $(this).text();

            refresh_student_status();
        })

        $('.save_student').on('click', function(e){
            e.preventDefault();

            var input = $(this);
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            var formData = new FormData($('#student_form')[0]);
            
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/save_student',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                    swal('Success!', 'Record has been saved to the Database!', 'success');
                    $('#student_modal').modal('hide');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    refresh_student_branch();
                    refresh_student_status();
                },
                error: function(data){
                    swal("Oh no!", "Something went wrong, try again.", "error");
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                }
            })
        })

        //Open Student Modal (ADD)
        $('.add_student').on('click', function(){
            $('#add_edit').val('add');
        });

        //Open Student Modal (EDIT)
        $(document).on('click', '.edit_student', function(){
            var id = $(this).attr('id');

            $.ajax({
                url: '/get_student',
                method: 'get',
                data: {id: id},
                dataType: 'json',
                success:function(data){
                    console.log(data);
                    $('#add_edit').val('edit');
                    $('#id').val(data.id);
                    $('#fname').val(data.fname);
                    $('#mname').val(data.mname);
                    $('#lname').val(data.lname);
                    $('#birthdate').val(data.birthdate);
                    $('#age').val(data.age);
                    $('#contact').val(data.contact);
                    $('#program').val(data.program.id).trigger('change');
                    $('#school').val(data.school.id).trigger('change');
                    $('#benefactor').val(data.benefactor.id).trigger('change');
                    $('#address').val(data.address);
                    $('#email').val(data.email);
                    $('#sign_up').val(data.date_of_signup);
                    $('#medical').val(data.date_of_medical);
                    $('#completion').val(data.date_of_completion);
                    $('#referral').val(data.referral.id).trigger('change');
                    $('#gender').val(data.gender).trigger('change');
                    $('#branch').val(data.branch.id).trigger('change');
                    $('#course').val(data.course);
                    $('#year').val(data.departure_year.id).trigger('change');
                    $('#month').val(data.departure_month.id).trigger('change');
                    $('#student_modal').modal('toggle');
                    $('#student_modal').modal('show');
                }
            });
        });

        //Delete Student
        $(document).on('click', '.delete_student', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Are you sure?',
                text: 'You are about to delete a student. This may affect multiple rows',
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
                        url: '/delete_student',
                        method: 'get',
                        data: {id:id},
                        type: 'json',
                        success:function(data){
                            swal('Deleted!', 'This Student has been Deleted', 'success');
                            refresh_student_branch();
                            refresh_student_status();
                        }
                    });
                }
            });
        });

        //Final School
        $(document).on('click', '.final_student', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Go for Final School?',
                text: 'This Student will be in Final School',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: '/final_student',
                        method: 'get',
                        data: {id: id},
                        dataType: 'text',
                        success: function(data){
                            swal('Congratulations!', 'This Student is now in Final School!', 'success');
                            refresh_student_branch();
                            refresh_student_status();
                        }
                    });
                }
            });
        });

        //Back Out
        $(document).on('click', '.backout_student', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Student will backout?',
                text: 'This Student will be transferred to list of backouts',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: '/backout_student',
                        method: 'get',
                        data: {id: id},
                        dataType: 'text',
                        success: function(data){
                            swal('Success!', 'This Student has backed out!', 'success');
                            refresh_student_branch();
                            refresh_student_status();
                        }
                    });
                }
            });
        });

        //Continue (From Back Out)
        $(document).on('click', '.continue_student', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Student will apply again?',
                text: 'This Student will be transferred to list of Active Students',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: '/continue_student',
                        method: 'get',
                        data: {id: id},
                        dataType: 'text',
                        success: function(data){
                            swal('Congratulations!', 'This Student is now active again!', 'success');
                            refresh_student_branch();
                            refresh_student_status();
                        }
                    });
                }
            });
        });

        //FUNCTIONS -- END
    });
</script>

@endsection