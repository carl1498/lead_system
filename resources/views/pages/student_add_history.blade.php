@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Delete History
    </h1>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <table id="student_add_history" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Program</th>
                        <th>Type</th>
                        <th>Added By</th>
                        <th>Added On</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</section>
@endsection

@section('script')
<script src="/js/pages/student_add_history.js"></script>
@endsection