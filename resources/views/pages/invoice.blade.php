@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Invoice Page
    </h1>
    <ol class="breadcrumb">
        <li>
            @if(canAccessAll() || canEditInvoice())
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Books" class="btn btn-secondary bg-red add_books">
                <i class="fas fa-book-medical"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Invoice" class="btn btn-secondary bg-red add_invoice">
                <i class="fas fa-file-medical"></i>
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
                    <li class="invoice_pick active"><a class="invoice_pick" href="#invoice_tab" data-toggle="tab">Invoice</a></li>
                    <li class="invoice_pick"><a class="invoice_pick" href="#add_books_tab" data-toggle="tab">Add Book History</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.invoice_select')
                    @include('includes.tabs.invoice_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    
    @include('includes.modals.invoice_modal')
    @include('includes.modals.add_books_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="/js/pages/invoice.js"></script>
@endsection