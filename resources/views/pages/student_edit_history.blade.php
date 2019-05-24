@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Edit History
    </h1>
    <ol class="breadcrumb">
        <li>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Refresh Table" class="btn btn-secondary bg-red refresh_table">
                <i class="fa fa-sync"></i>
            </button>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <table id="student_edit_history" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Field</th>
                        <th>Previous</th>
                        <th>New</th>
                        <th>Edited By</th>
                        <th>Edited On</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</section>
@endsection

@section('script')
<script src="/js/pages/student_edit_history.js"></script>
@endsection