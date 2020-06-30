@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Expense Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table" disabled>
                <i class="fa fa-sync"></i>
            </button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add"  class="btn btn-secondary bg-red add_expense">
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
                    <li class="active expense_pick"><a class="expense_pick expense_picked" href="#expense_tab" data-toggle="tab">Expense</a></li>
                    @if(ExpenseHigherPermission())
                    <li class="expense_pick"><a class="expense_pick expense_picked" href="#type_tab" data-toggle="tab">Type</a></li>
                    <li class="expense_pick"><a class="expense_pick expense_picked" href="#particular_tab" data-toggle="tab">Particular</a></li>
                    @endif
                    <li class="expense_pick"><a class="expense_pick expense_picked" href="#cash_disbursement_tab" data-toggle="tab">Cash Disbursement Journal</a></li>
                    <li class="expense_pick"><a class="expense_pick expense_picked" href="#fiscal_year_tab" data-toggle="tab">Fiscal Year</a></li>
                </ul>

                <div class="tab-content">
                    
                    @include('includes.expense_select')
                    @include('includes.tabs.expense_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.expense_modals.expense_type_modal')
    @include('includes.modals.expense_modals.expense_particular_modal')
    @include('includes.modals.expense_modals.expense_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script src="{{ mix('/js/pages/expense.js') }}"></script>
@endsection