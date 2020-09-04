@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Client Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table">
                <i class="fa fa-sync"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Client" class="btn btn-secondary bg-red add_client">
                <i class="fas fa-plus-square"></i>
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
                    <li class="disabled client_pick active"><a class="client_pick" href="#client_tab" data-toggle="tab">Japanese School</a></li>
                    <li class="disabled client_pick"><a class="client_pick" href="#client_tab" data-toggle="tab">POEA Agency</a></li>
                    <li class="disabled client_pick"><a class="client_pick" href="#client_tab" data-toggle="tab">SSW Benefactor</a></li>
                    <li class="disabled client_pick"><a class="client_pick" href="#client_tab" data-toggle="tab">TITP</a></li>
                    <li class="disabled client_pick"><a class="client_pick" href="#client_tab" data-toggle="tab">RSO</a></li>
                    <li class="disabled client_pick"><a class="client_pick" href="#client_tab" data-toggle="tab">Others</a></li>
                </ul>

                <div class="tab-content">
                
                    <div class="tab-pane fade in active" id="client_tab">
                        <table id="client_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
                            <thead>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Contact #</th>
                                <th>Email</th>
                                <th>Action</th>
                            </thead>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->

    @include('includes.modals.client_modals.client_modal')
    @include('includes.modals.client_modals.client_view_pic_modal')
    @include('includes.modals.client_modals.client_pic_modal')
    @include('includes.modals.client_modals.client_bank_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/client.js') }}"></script>
@endsection