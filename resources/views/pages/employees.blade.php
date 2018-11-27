@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Employee Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_employee" data-toggle="modal" data-target="#employee_modal">
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
                    <li class="active"><a href="#employees_makati_tab" data-toggle="tab">Makati</a></li>
                    <li><a href="#employees_naga_tab" data-toggle="tab">Naga</a></li>
                    <li><a href="#employees_cebu_tab" data-toggle="tab">Cebu</a></li>
                    <li><a href="#employees_davao_tab" data-toggle="tab">Davao</a></li>
                    
                </ul>

                <div class="tab-content">

                    @include('includes.tabs.employee_tabs')

                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.employee_modal')

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

        var columns_employees = [
            {data: 'name', name: 'name'},
            {data: 'contact_personal', name: 'contact_personal'},
            {data: 'contact_business', name: 'contact_business'},
            {data: 'birthdate', name: 'birthdate'},
            {data: 'gender', name: 'gender'},
            {data: 'email', name: 'email'},
            {data: 'role.name', name: 'role.name'},
            {data: 'hired_date', name: 'hired_date'},
            {data: "action", orderable:false,searchable:false}
        ]

        const employees_table_variables = [employees_makati, employees_naga, employees_cebu, employees_davao];
        const employees_table_id = ['employees_makati', 'employees_naga', 'employees_cebu', 'employees_davao'];
        const employees_table_route = ['/makatiEmployee', '/nagaEmployee', '/cebuEmployee', '/davaoEmployee'];

        for(var x = 0; x < 4; x++){
            employees_table_variables[x] = $('#'+employees_table_id[x]+ "").DataTable({
                scrollX:        true,
                scrollCollapse: true,
                fixedColumns:   true,
                ajax: employees_table_route[x],
                columns: columns_employees,
            });
        }

        //DATATABLES -- END

        //FUNCTIONS -- START

        $('.save_student').on('click', function(e){
            e.preventDefault();
            console.log($('#employee_form').serialize());
        })

        //FUNCTIONS -- END
    });
</script>

@endsection