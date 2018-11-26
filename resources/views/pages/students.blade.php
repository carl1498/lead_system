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
                    <li class="active"><a href="#students_makati_tab" data-toggle="tab">Makati</a></li>
                    <li><a href="#students_naga_tab" data-toggle="tab">Naga</a></li>
                    <li><a href="#students_cebu_tab" data-toggle="tab">Cebu</a></li>
                    <li><a href="#students_davao_tab" data-toggle="tab">Davao</a></li>
                    
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
        
        $('.datepicker').datepicker();

        $('.select2').select2();

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

        const students_table_variables = [students_makati, students_naga, students_cebu, students_davao];
        const students_table_id = ['students_makati', 'students_naga', 'students_cebu', 'students_davao'];
        const students_table_route = ['/makatiStudent', 'nagaStudent', 'cebuStudent', 'davaoStudent'];

        for(var x = 0; x < 4; x++){
            students_table_variables[x] = $('#'+students_table_id[x]+ "").DataTable({
                scrollX:        true,
                scrollCollapse: true,
                fixedColumns:   true,
                ajax: students_table_route[x],
                columns: columns_students,
            });
        }
        //DATATABLES -- END
    });
</script>

@endsection