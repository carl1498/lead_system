@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Salary Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add" class="btn btn-secondary bg-red add_salary">
                <i class="fa fa-plus-square"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Bulk Add" class="btn btn-secondary bg-red bulk_add_salary">
                <i class="fa fa-plus-circle"></i>
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
                    <li class="active tab_pick"><a class="tab_pick tab_picked" href="#emp_salary_tab" data-toggle="tab">Employee</a></li>
                    <li class="tab_pick"><a class="tab_pick" href="#salary_tab" data-toggle="tab">Salary</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.salary_select')
                    @include('includes.tabs.salary_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->

    @include('includes.modals.salary_modals.filter_modal')
    @include('includes.modals.salary_modals.emp_salary_modal')
    @include('includes.modals.salary_modals.salary_modal')
    @include('includes.modals.salary_modals.bulk_salary_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/salary.js') }}"></script>
@endsection