@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Employee Page
    </h1>
    <ol class="breadcrumb">
        <li><button class="btn btn-secondary bg-red"><i class="fa fa-plus-square"></i></button></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="./img/user.jpg" alt="User profile picture">

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
                    <li class="active"><a href="#makati_tab" data-toggle="tab">Makati</a></li>
                    <li><a href="#timeline" data-toggle="tab">Naga</a></li>
                    <li><a href="#settings" data-toggle="tab">Cebu</a></li>
                    <li><a href="#settings" data-toggle="tab">Davao</a></li>
                </ul>
                <div class="tab-content">
                    <div class="active tab-pane" id="makati_tab">
                        <table id="makati" class="table table-striped table-bordered" cellspacing="0" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Hired Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <!-- /.tab-pane -->
                    <div class="tab-pane" id="timeline">
                    </div>
                <!-- /.tab-pane -->
                </div>
            <!-- /.tab-content -->
            </div>
        <!-- /.nav-tabs-custom -->
        </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->

</section>
    
@endsection