@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Invoice Page
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
                    <li class="active"><a class="settings_pick" href="#invoice_tab" data-toggle="tab">Invoice</a></li>
                    <li><a class="settings_pick" href="#invoice_tab2" data-toggle="tab">Add Book History</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.tabs.invoice_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        //INITIALIZE -- END


        //DATATABLES -- START


        //DATATABLES -- END


        //FUNCTIONS -- START

        //FUNCTIONS -- END
    });
</script>

@endsection