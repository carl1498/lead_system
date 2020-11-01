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
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Security Bond"  class="btn btn-secondary bg-red sb_payment">
                <i class="fa fa-shield-alt"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Payment"  class="btn btn-secondary bg-red tf_payment">
                <i class="fa fa-money-check-alt"></i>
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
                    <li class="payment_pick"><a class="payment_pick" href="#payment_tab" data-toggle="tab">Payment History</a></li>
                    <li class="sec_bond_pick"><a class="sec_bond_pick" href="#sec_bond_tab" data-toggle="tab">Security Bond History</a></li>
                    <li class="program_pick"><a class="program_pick" href="#program_tab" data-toggle="tab">Programs</a></li>
                    <li class="tf_breakdown_pick"><a class="tf_breakdown_pick" href="#tf_breakdown_tab" data-toggle="tab">TF Breakdown</a></li>
                    <li class="summary_pick"><a class="summary_pick" href="#summary_tab" data-toggle="tab">Summary</a></li>
                    <li class="soa_pick"><a class="soa_pick" href="#soa_tab" data-toggle="tab">SOA</a></li>
                </ul>

                <div class="tab-content">
                    
                @include('includes.tuition_select')
                @include('includes.tabs.tuition_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.tuition_modals.filter_modal')
    @include('includes.modals.tuition_modals.projection_modal')
    @include('includes.modals.tuition_modals.tf_payment_modal')
    @include('includes.modals.tuition_modals.sb_payment_modal')
    @include('includes.modals.tuition_modals.student_tuition_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/tuition.js') }}"></script>
@endsection