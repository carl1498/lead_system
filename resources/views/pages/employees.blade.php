@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Employee Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            @if(canAccessAll())
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Employee" class="btn btn-secondary bg-red add_employee">
                <i class="fa fa-plus-square"></i>
            </button>
            @endif
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
                    <div style="width: auto; height: 125px;">
                        <img id="p_picture" class="profile-user-img img-responsive img-rounded pull-left" src="./img/avatar5.png" alt="User Profile Picture" style="max-width: 97px; max-height: 97px;">
                        
                        <h4 id="p_emp_name" class="profile-username text-center">Employee Name</h4>

                        <p id="p_position" class="text-muted text-center">Position</p>
                    </div>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <strong>Business #</strong>
                            <p id="p_business" class="text-muted">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>Personal #</strong>
                            <p id="p_personal" class="text-muted">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <b>Birthdate</b> <p id="p_birthdate" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Gender</b> <p id="p_gender" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Branch</b> <p id="p_branch" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Employment Status</b> <p id="p_status" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Hired Date</b> <p id="p_hired" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Resignation Date</b> <p id="p_resigned" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <strong>SSS</strong>
                            <p id="p_sss" class="text-muted" style="word-wrap: break-word;">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>Pagibig</strong>
                            <p id="p_pagibig" class="text-muted" style="word-wrap: break-word;">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>Philhealth</strong>
                            <p id="p_philhealth" class="text-muted" style="word-wrap: break-word;">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>TIN</strong>
                            <p id="p_tin" class="text-muted" style="word-wrap: break-word;">
                            -
                            </p>
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
                    <li class="disabled tab_pick active"><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">Makati</a></li>
                    <li class="disabled tab_pick"><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">Cebu</a></li>
                    <li class="disabled tab_pick"><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">Davao</a></li>
                </ul>

                <div class="tab-content">

                    @include('includes.employee_status_select')
                    @include('includes.tabs.employee_tabs')

                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.employee_modal')
    @include('includes.modals.account_modal')
    @include('includes.modals.resign_modal')
    @include('includes.modals.rehire_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="/js/pages/employees.js"></script>
@endsection