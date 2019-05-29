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
<script src="{{ mix('/js/pages/student_settings.js') }}"></script>
@endsection