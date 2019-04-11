@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Edit History
    </h1>
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
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        //INITIALIZE -- END

        //DATATABLES -- START

        function load(){
            var student_edit_history = $('#student_edit_history').DataTable({
                destroy: true,
                stateSave: true,
                stateSaveCallback: function(settings,data) {
                    localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
                },
                stateLoadCallback: function(settings) {
                    return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                },
                ajax: '/student_edit_history_table',
                columns: [
                    {data: 'stud_id', name: 'student'},
                    {data: 'field', name: 'field'},
                    {data: 'previous', name: 'previous'},
                    {data: 'new', name: 'new'},
                    {data: 'edited_by.fname', name: 'edited_by'},
                    {data: 'created_at', name: 'created_at'},
                ],
                columnDefs: [
                    { width: 150, targets: 0},
                    { width: 90, targets: 1},
                    { width: 70, targets: 4},
                    { width: 110, targets: 5}
                ],
                order: [[5, 'desc']]
            });
        }

        //DATATABLES -- END

        //FUNCTIONS -- START

        load();

        setInterval(function(){
            load();
        }, 300000);

        //FUNCTIONS -- END
    });
</script>

@endsection