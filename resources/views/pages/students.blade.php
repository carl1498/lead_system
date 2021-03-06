@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Page
    </h1>
    <ol class="breadcrumb">
        <li>
            @if(canAccessAll() || canAccessStudentList())
            <div class="btn-group" role="group" aria-label="switch">
                <button title="Switch Tabs" class="btn btn-secondary bg-red switch switch_student" disabled>
                    <b class="switch_name">Student</b>
                </button>
                <button class="btn btn-secondary bg-red switch switch_ssw" disabled>
                    <b class="switch_name">SSW</b>
                </button>
                <button class="btn btn-secondary bg-red switch switch_titp" disabled>
                    <b class="switch_name">TITP</b>
                </button>
                <button class="btn btn-secondary bg-red switch switch_intern" disabled>
                    <b class="switch_name">Intern</b>
                </button>
            </div>

            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Student" class="btn btn-secondary bg-red add_student">
                <i class="fa fa-plus-square"></i>
            </button>
            @endif
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-3 col-xl-2">

            <!-- Profile Image -->
            <div id="scroller-anchor"></div> 
            <div class="box box-primary" id="box-primary-fixed">
                <div class="box-body box-profile">
                    <div style="width: auto; height: 200px;">
                        @if(studentHigherPermission())
                        <button disabled class="btn btn-primary btn-sm print_student_profile"
                            style="position: absolute; right: 20px; z-index: 1000;">
                            <i class="fa fa-print"></i>
                        </button>
                        @endif

                        <img id="p_picture" class="profile-user-img img-responsive img-rounded" src="./img/avatar5.png" alt="Student Profile Picture" style="max-width: 97px; max-height: 97px;">

                        <h4 id="p_stud_name" class="profile-username text-center">Student Name</h4>

                        <p id="p_departure" class="text-muted text-center">Departure</p>
                    </div>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <strong>Contact</strong>
                            <p id="p_contact" class="text-muted" style="word-wrap: break-word;">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>Batch (SSW)</strong>
                            <p id="p_batch" class="text-muted">
                            -
                            </p>   
                        </li> 
                        <li class="list-group-item">
                            <strong>Program</strong>
                            <p id="p_program" class="text-muted">
                            -
                            </p>   
                        </li> 
                        <li class="list-group-item">          
                            <strong>School</strong>
                            <p id="p_school" class="text-muted">
                            -
                            </p>  
                        </li> 
                        <li class="list-group-item">
                            <strong>Benefactor</strong>
                            <p id="p_benefactor" class="text-muted">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>University</strong>
                            <p id="p_university" class="text-muted">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>Company (For TITP Only)</strong>
                            <p id="p_company" class="text-muted">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <b>Birthdate</b> <p id="p_birthdate" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Civil Status</b> <p id="p_civil" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Gender</b> <p id="p_gender" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Referral</b> <p id="p_referral" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Sign Up</b> <p id="p_sign_up" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Medical</b> <p id="p_medical" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Completion</b> <p id="p_completion" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Branch</b> <p id="p_branch" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>Status</b> <p id="p_status" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <b>COE Status</b> <p id="p_coe_status" class="pull-right text-muted">-</p>
                        </li>
                        <li class="list-group-item">
                            <strong>Email</strong>
                            <p id="p_email" class="text-muted" style="word-wrap: break-word;">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>Course</strong>
                            <p id="p_course" class="text-muted">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>Address</strong>
                            <p id="p_address" class="text-muted">
                            -
                            </p>
                        </li>

                        <li class="list-group-item">
                            <strong>Remarks</strong>
                            <p id="p_remarks" class="text-muted">
                            -
                            </p>
                        </li>
                        <li class="list-group-item">
                            <strong>In case of Emergency</strong>
                            <p id="p_emergency" class="text-muted" style="word-wrap: break-word;">
                            -
                            </p>
                        </li>
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->

        <div class="col-md-9 col-xl-10">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs" id="student_list_tab">
                    <!-- Intern -->
                    <li class="disabled intern_pick" style="display: none;"><a style="display: none;" id="intern_first" class="intern_pick" href="#intern_student_tab" data-toggle="tab">Intern</a></li>
                    <li class="disabled intern_pick" style="display: none;"><a style="display: none;" class="intern_pick" href="#intern_student_tab" data-toggle="tab">Back Out</a></li>

                    <!-- TITP -->
                    <li class="disabled titp_pick" style="display: none;"><a style="display: none;" id="titp_first" class="titp_pick" href="#titp_student_tab" data-toggle="tab">TITP</a></li>
                    <li class="disabled titp_pick" style="display: none;"><a style="display: none;" class="titp_pick" href="#titp_student_tab" data-toggle="tab">Back Out</a></li>
                    
                    <!-- SSW -->
                    <li class="disabled ssw_pick" style="display: none;"><a style="display: none;" id="ssw_first" class="ssw_pick" href="#ssw_student_tab" data-toggle="tab">SSW</a></li>
                    <li class="disabled ssw_pick" style="display: none;"><a style="display: none;" class="ssw_pick" href="#ssw_student_tab" data-toggle="tab">Back Out</a></li>
                    
                    <!-- Student/Language -->
                    <li class="disabled branch_pick active"><a id="student_first" class="branch_pick" href="#students_branch_tab" data-toggle="tab">Makati</a></li>
                    <li class="disabled branch_pick"><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Cebu</a></li>
                    <li class="disabled branch_pick"><a class="branch_pick" href="#students_branch_tab" data-toggle="tab">Davao</a></li>
                    <li class="disabled status_pick"><a class="status_pick" href="#students_status_tab" data-toggle="tab">Final School</a></li>
                    <li class="disabled status_pick"><a class="status_pick" href="#students_status_tab" data-toggle="tab">Back Out / Cancelled</a></li>
                    <li class="disabled result_pick"><a class="result_pick" href="#students_result_tab" data-toggle="tab">Result Monitoring</a></li>
                    <li class="disabled language_pick"><a class="language_pick" href="#language_student_tab" data-toggle="tab">Language</a></li>

                    <li class="disabled all_pick"><a class="all_pick" href="#all_student_tab" data-toggle="tab">All</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.student_select')
                    @include('includes.tabs.student_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.student_modals.student_modal')
    @if(studentHigherPermission())
        @include('includes.modals.student_modals.student_info_modal')
        @include('includes.modals.student_modals.student_emergency_modal')
        @include('includes.modals.student_modals.student_emp_history_modal')
        @include('includes.modals.student_modals.student_educational_background_modal')
        @include('includes.modals.student_modals.student_soa_modal')
    @endif

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/students.js') }}"></script>
@endsection