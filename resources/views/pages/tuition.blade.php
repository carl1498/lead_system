@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Tuition Fee Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add"  class="btn btn-secondary bg-red add_student">
                <i class="fa fa-user-plus"></i>
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
                    <li class="active student_pick"><a class="student_pick" href="#student_tab" data-toggle="tab">Student</a></li>
                    <li class="tuition_sec_pick"><a class="tuition_sec_pick" href="#tuition_sec_tab" data-toggle="tab">Tuition Fee Payment History</a></li>
                    <li class="tuition_sec_pick"><a class="tuition_sec_pick" href="#tuition_sec_tab" data-toggle="tab">Security Bond Payment History</a></li>
                    <li class="program_pick"><a class="program_pick" href="#program_tab" data-toggle="tab">Programs</a></li>
                </ul>

                <div class="tab-content">
                    
                @include('includes.tabs.tuition_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.tuition_modals.projection_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/tuition.js') }}"></script>
@endsection