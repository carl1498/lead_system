@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Class Page
    </h1>
    <ol class="breadcrumb">
        <li>
            @if(canAccessAll() || canEditInvoice())
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
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
                        <button class="btn btn-md btn-info">
                            <b>On-Going</b>
                            <span class="label label-default">0</span>
                        </button>
                        <button class="btn btn-md btn-info">
                            <b>Complete</b>
                            <span class="label label-default">100</span>
                        </button>
                        <button class="btn btn-md btn-info">
                            <b>All</b>
                            <span class="label label-default">1000</span>
                        </button>
                    </div>
                </div>
                <div class="box-body box-profile" id="test1">
                        <li class="list-group-item">
                            <p style="word-wrap: break-word;">
                                2019-1-1 ~ TBA<br>
                                <b>Angel Anterola</b><br>
                                <span>M • Tu • W • Th • F • Sa</span><br>
                                <span class="label label-success">0</span>
                                <span class="label label-danger">0</span>
                                <span class="label label-primary">0</span>
                                <span class="label label-warning">0</span>
                            </p>
                        </li>
                        <li class="list-group-item">
                            <p style="word-wrap: break-word;">
                                2019-1-1 ~ TBA<br>
                                <b>Angel Anterola</b><br>
                                <span>M • Tu • W • Th • F • Sa</span><br>
                                <span class="label label-success">0</span>
                                <span class="label label-danger">0</span>
                                <span class="label label-primary">0</span>
                                <span class="label label-warning">0</span>
                            </p>
                        </li>
                    </ul>

                </div>
                <div class="box-body box-profile" id="test2">
                        <li class="list-group-item">
                            <p style="word-wrap: break-word;">
                                2019-1-1 ~ 2019-2-2<br>
                                <b>Angel Anterola</b><br>
                                <span>M • Tu • W • Th • F • Sa</span><br>
                                <span class="label label-success">0</span>
                                <span class="label label-danger">0</span>
                                <span class="label label-primary">0</span>
                                <span class="label label-warning">0</span>
                            </p>
                        </li>
                    </ul>

                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-8 col-xl-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="disabled tab_pick active"><a class="tab_pick" href="#employees_all_tab" data-toggle="tab">Students</a></li>
                    <li class="disabled tab_pick"><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">No Classes</a></li>
                    <li class="disabled tab_pick"><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">All</a></li>
                    <li class="disabled tab_pick"><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">Settings</a></li>
                </ul>

                <div class="tab-content">
                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -- START -->

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/student_class.js') }}"></script>
@endsection