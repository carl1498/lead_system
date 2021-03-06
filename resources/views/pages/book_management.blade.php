@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Book Management Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            @if(canAccessAll() || canEditBookManagement())
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Assign Books" class="btn btn-secondary bg-red assign_books">
            <i class="fas fa-people-carry"></i>
            </button>
            @endif
            @if((canAccessAll() || canEditBookManagement()) && onLoadBranch() == 'Makati')
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Release Books" class="btn btn-secondary bg-red release_books">
            <i class="fas fa-external-link-alt"></i>
            </button>
            @endif
            @if((canAccessAll() || canEditBookManagement()) && onLoadBranch() != 'Makati')
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Request Books" class="btn btn-secondary bg-red request_books">
            <i class="fas fa-hand-holding"></i>
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
                    <li class="disabled books_pick active"><a class="books_pick" href="#branch_tab" data-toggle="tab">Branch</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#ssw_student_tab" data-toggle="tab">SSW</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#student_tab" data-toggle="tab">Student</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#books_tab" data-toggle="tab">Books</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#request_tab" data-toggle="tab">Request History</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#release_tab" data-toggle="tab">Release History</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#assign_tab" data-toggle="tab">Assign History</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#return_tab" data-toggle="tab">Return History</a></li>
                    <li class="disabled books_pick"><a class="books_pick" href="#lost_tab" data-toggle="tab">Lost History</a></li>
                </ul>

                <div class="tab-content">
                
                    @include('includes.books_select')
                    @include('includes.tabs.book_management_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.book_management_modals.request_books_modal')
    @include('includes.modals.book_management_modals.release_books_modal')
    @include('includes.modals.book_management_modals.assign_books_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/book_management.js') }}"></script>
@endsection