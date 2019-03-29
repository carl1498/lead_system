@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-network-wired"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Your Referrals</span>
                <span class="info-box-number">{{ $referral_count }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-user-graduate"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Students</span>
                <span class="info-box-number">{{ $student_count }}</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->

    <div class="row">
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
@endsection