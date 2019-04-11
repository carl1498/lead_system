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
            <table id="student_delete_history" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Deleted By</th>
                        <th>Deleted On</th>
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
            var student_delete_history = $('#student_delete_history').DataTable({
                destroy: true,
                stateSave: true,
                stateSaveCallback: function(settings,data) {
                    localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
                },
                stateLoadCallback: function(settings) {
                    return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
                },
                ajax: '/student_delete_history_table',
                columns: [
                    {data: 'stud_id', name: 'student'},
                    {data: 'deleted_by.fname', name: 'deleted_by'},
                    {data: 'created_at', name: 'created_at'},
                ],
                order: [[2, 'desc']]
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