@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Order Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table">
                <i class="fa fa-sync"></i>
            </button>
            @if(canAccessAll() || orderHigherPermission())
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Order" class="btn btn-secondary bg-red add_order">
                <i class="fas fa-plus-square"></i>
            </button>
            @endif
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
                    <li class="disabled order_pick active"><a class="order_pick" href="#order_tab" data-toggle="tab">Student</a></li>
                    <li class="disabled order_pick"><a class="order_pick" href="#order_tab" data-toggle="tab">SSW</a></li>
                    <li class="disabled order_pick"><a class="order_pick" href="#order_tab" data-toggle="tab">Internship</a></li>
                    <li class="disabled order_pick"><a class="order_pick" href="#order_tab" data-toggle="tab">EHI</a></li>
                    <li class="disabled order_pick"><a class="order_pick" href="#order_tab" data-toggle="tab">TITP</a></li>
                    <li class="disabled order_pick"><a class="order_pick" href="#order_tab" data-toggle="tab">Others</a></li>
                </ul>

                <div class="tab-content">
                
                    <div class="tab-pane fade in active" id="order_tab">
                        <table id="order_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
                            <thead>
                                <th>Client</th>
                                <th>Order Type</th>
                                <th>Orders</th>
                                <th>Hires</th>
                                <th>Interview Date</th>
                                <th>Time</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </thead>
                            <tfoot>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tfoot>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->

    @include('includes.modals.order_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/order.js') }}"></script>
@endsection