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
                    <!--<img class="profile-user-img img-responsive img-circle" src="./img/avatar5.png" alt="User profile picture">-->

                    <h3 class="profile-username text-center">Student Name</h3>

                    <!--<p class="text-muted text-center"></p>-->

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b>Contact</b> <a class="pull-right">test</a>
                        </li>
                        <li class="list-group-item">
                            <b>Program</b> <a class="pull-right">test</a>
                        </li>
                        <li class="list-group-item">
                            <b>School</b> <a class="pull-right">test</a>
                        </li>
                        <li class="list-group-item">
                            <b>Benefactor</b> <a class="pull-right">test</a>
                        </li>
                    </ul>

                    <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
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
                    <li><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Cebu</a></li>
                    <li><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Davao</a></li>
                    <li><a class="status_pick" href="#students_status_tab" data-toggle="tab">Final School</a></li>
                    <li><a class="status_pick" href="#students_status_tab" data-toggle="tab">Back Out / Cancelled</a></li>
                    <li><a class="result_pick" href="#students_result_tab" data-toggle="tab">Result Monitoring</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.departures')
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
        var current_result = '';
        var departure_year = $('#year_select').val();
        var departure_month = $('#month_select').val();

        var students_branch;
        var students_status;
        var students_result;
        
        $(".datepicker").datepicker({
            format: 'yyyy-mm-dd',
            forceParse: false
        });

        $('.select2').select2();
        
        $('body').tooltip({
            selector: '[data-toggle="tooltip"]',
            trigger : 'hover'
        });

        /*function refresh_tables(){//reload datatable ajax
            students_branch.ajax.reload();
        }*/

        $('#student_modal').on('shown.bs.modal', function(){
            $('#fname').focus();
        });

        $("#student_modal").on("hidden.bs.modal", function(e){
            $('#student_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
            $('.select2').trigger('change.select2');
        });

        $('input, select').attr('autocomplete', 'off');

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
            {data: 'course.name', name: 'course'},
            {data: 'date_of_signup', name: 'date_of_signup'},
            {data: 'referral.fname', name: 'referral'},
            {data: 'remarks', name: 'remarks'},
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
            { width: 250, targets: 10 },
            { width: 150, targets: 11 },
            {defaultContent: "", targets: "_all"}
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
            {data: 'course.name', name: 'course'},
            {data: 'date_of_signup', name: 'date_of_signup'},
            {data: 'referral.fname', name: 'referral'},
            {data: 'status', name: 'status'},
            {data: 'remarks', name: 'remarks'},
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
            { width: 100, targets: 11 },
            { width: 250, targets: 12 },
            { width: 150, targets: 13 },
            {defaultContent: "", targets: "_all"}
        ]

        var columns_students_result = [
            {data: 'name', name: 'name'},
            {data: 'branch.name', name: 'branch'},
            {data: 'program.name', name: 'program'},
            {data: 'school.name', name: 'school'},
            {data: 'coe_status', name: 'coe_status'},
            {data: 'status', name: 'status'},
            {data: 'referral.fname', name: 'referral'},
            {data: "action", orderable:false,searchable:false}
        ]
        
        var columnDefs_students_result = [
            { width: 220, targets: 0 },
            { width: 70, targets: 1 },
            { width: 90, targets: 2 },
            { width: 130, targets: 3 },
            { width: 100, targets: 4 },
            { width: 100, targets: 5 },
            { width: 120, targets: 6 },
            { width: 150, targets: 7 },
            {defaultContent: "", targets: "_all"}
        ]

        function refresh_student_branch(){
            departure_year = $('#year_select').val();
            departure_month = $('#month_select').val();

            students_branch = $('#students_branch').DataTable({
                processing: true,
                destroy: true,
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: true,
                ajax: {
                    url: '/student_branch',
                    data: {
                        current_branch: current_branch,
                        departure_year: departure_year,
                        departure_month: departure_month
                    }
                },
                columnDefs: columnDefs_students,
                columns: columns_students,
            });

            $('.tooltip').css('width', '400px');
        }

        function refresh_student_status(){

            departure_year = $('#year_select').val();
            departure_month = $('#month_select').val();

            students_status = $('#students_status').DataTable({
                processing: true,
                destroy: true,
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: true,
                responsive: true,
                ajax: {
                    url: '/student_status',
                    data: {
                        current_status: current_status,
                        departure_year: departure_year,
                        departure_month: departure_month
                    }
                },
                columnDefs: columnDefs_students_status,
                columns: columns_students_status,
            });
        }

        function refresh_student_result(){

            departure_year = $('#year_select').val();
            departure_month = $('#month_select').val();

            students_result = $('#students_result').DataTable({
                processing: true,
                destroy: true,
                scrollX: true,
                scrollCollapse: true,
                fixedColumns: true,
                responsive: true,
                ajax: {
                    url: '/student_result',
                    data: {
                        current_result: current_result,
                        departure_year: departure_year,
                        departure_month: departure_month
                    }
                },
                columnDefs: columnDefs_students_result,
                columns: columns_students_result,
            });
        }

        refresh_student_branch();
        refresh_student_status();
        refresh_student_result();

        //DATATABLES -- END

        //FUNCTIONS -- START

        $('.branch_pick').on('click', function(){
            current_branch = $(this).text();
            
            refresh_student_branch();
        });

        $('.status_pick').on('click', function(){
            current_status = $(this).text();

            refresh_student_status();
        });

        $('.result_pick').on('click', function(){
            current_result = $(this).text();

            refresh_student_result();
        });

        $(document).on('change', '#year_select, #month_select', function(){
            refresh_student_branch();
            refresh_student_status();
            refresh_student_result();
        });

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
            });
        });

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
                    $('#add_edit').val('edit');
                    $('#id').val(data.id);
                    $('#fname').val(data.fname);
                    $('#mname').val(data.mname);
                    $('#lname').val(data.lname);
                    $('#birthdate').val(data.birthdate);
                    $('#age').val(data.age);
                    $('#contact').val(data.contact);

                    if(data.program){
                        $('#program').val(data.program.id).trigger('change');
                    }
                    if(data.school){
                        $('#school').val(data.school.id).trigger('change');
                    }
                    if(data.benefactor){
                        $('#benefactor').val(data.benefactor.id).trigger('change');
                    }
                    
                    $('#address').val(data.address);
                    $('#email').val(data.email);
                    $('#sign_up').val(data.date_of_signup);
                    $('#medical').val(data.date_of_medical);
                    $('#completion').val(data.date_of_completion);
                    $('#referral').val(data.referral.id).trigger('change');
                    $('#gender').val(data.gender).trigger('change');
                    $('#branch').val(data.branch.id).trigger('change');
                    $('#course').val(data.course.id).trigger('change');
                    $('#year').val(data.departure_year.id).trigger('change');
                    $('#month').val(data.departure_month.id).trigger('change');
                    $('#remarks').val(data.remarks);
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
                            if(current_status == 'Final School'){
                                swal('Success!', 'This Student out of Final School!', 'success');
                            }
                            else{
                                swal('Congratulations!', 'This Student is now active again!', 'success');
                            }
                            refresh_student_branch();
                            refresh_student_status();
                        }
                    });
                }
            });
        });

        //Approve (Only in Result Monitoring)
        $(document).on('click', '.approve_student', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Student COE Approved?',
                text: 'Confirm that this student\'s COE is approved?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: '/approve_student',
                        method: 'get',
                        data: {id: id},
                        dataType: 'text',
                        success: function(data){
                            swal('Congratulations!', 'Student COE Approved!', 'success');

                            refresh_student_branch();
                            refresh_student_status();
                            refresh_student_result();
                        }
                    });
                }
            });
        });

        //Deny (Only in Result Monitoring)
        $(document).on('click', '.deny_student', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Student COE Denied?',
                text: 'Confirm that this student\'s COE is denied?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: '/deny_student',
                        method: 'get',
                        data: {id: id},
                        dataType: 'text',
                        success: function(data){
                            swal('Alright', 'Student COE Denied', 'success');

                            refresh_student_branch();
                            refresh_student_status();
                            refresh_student_result();
                        }
                    });
                }
            });
        });

        //Cancel (Only in Result Monitoring)
        $(document).on('click', '.cancel_student', function(){
            var id = $(this).attr('id');

            swal({
                title: 'Student will Cancel?',
                text: 'Confirm that this student decided to cancel?',
                type: 'info',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes!'
            }).then((result) => {
                if(result.value){
                    $.ajax({
                        url: '/cancel_student',
                        method: 'get',
                        data: {id: id},
                        dataType: 'text',
                        success: function(data){
                            swal('Alright', 'Student Cancelled', 'success');

                            refresh_student_branch();
                            refresh_student_status();
                            refresh_student_result();
                        }
                    });
                }
            });
        });

        //FUNCTIONS -- END
    });
</script>

@endsection