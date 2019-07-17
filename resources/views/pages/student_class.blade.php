@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Class Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            @if(canAccessAll() || StudentClassHigherPermission())
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Assign Student Class" class="btn btn-secondary bg-red assign_student_class">
                <i class="fas fa-user-plus"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Class" class="btn btn-secondary bg-red add_class">
                <i class="fas fa-plus-square"></i>
            </button>
            @endif
        </li>
    </ol>
</section>

<section class="content">

    <div class="row">
        <div class="col-md-4 col-xl-3">

            <!-- Profile Image -->
            <div id="scroller-anchor"></div> 
            <div class="box box-primary">
                <div class="text-center">
                    <div class="btn-group" role="group" aria-label="switch">
                        <button class="btn btn-md btn-info class_nav_box" id="on_going_class_box" disabled>
                            <b>Ongoing</b>
                            <span class="label label-default">0</span>
                        </button>
                        <button class="btn btn-md btn-info class_nav_box" id="complete_class_box">
                            <b>Complete</b>
                            <span class="label label-default">0</span>
                        </button>
                        <button class="btn btn-md btn-info class_nav_box" id="all_class_box">
                            <b>All</b>
                            <span class="label label-default">0</span>
                        </button>
                    </div>
                </div>
                <div class="box-body box-profile" id="class_box">

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8 col-xl-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="disabled tab_pick stud_pick active"><a class="tab_pick stud_pick" href="#student_class_tab" data-toggle="tab">Students</a></li>
                    <li class="disabled tab_pick stud_pick"><a class="tab_pick stud_pick" href="#student_no_class_tab" data-toggle="tab">No Classes</a></li>
                    <li class="disabled tab_pick stud_pick"><a class="tab_pick stud_pick" href="#student_all_class_tab" data-toggle="tab">All</a></li>
                    <li class="disabled tab_pick"><a class="tab_pick" href="#class_settings_tab" data-toggle="tab">Settings</a></li>
                </ul>

                <div class="tab-content">
                
                @include('includes.tabs.student_class_tabs')

                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -- START -->

    @include('includes.modals.student_class_modals.student_class_history_modal')
    @include('includes.modals.student_class_modals.add_class_modal')
    @include('includes.modals.student_class_modals.assign_student_class_modal')
    @include('includes.modals.student_class_modals.edit_student_date_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/student_class.js') }}"></script>
@endsection