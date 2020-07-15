$(document).ready(function(){

    //INITIALIZE -- START

    //INITIALIZE -- END

    //DATATABLES -- START

    function load(){
        var student_add_history = $('#student_add_history').DataTable({
            destroy: true,
            stateSave: true,
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            stateSaveCallback: function(settings,data) {
                localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
            },
            stateLoadCallback: function(settings) {
                return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
            },
            stateLoadParams: function( settings, data ) {
                if (data.order) delete data.order;
            },
            ajax: '/student_add_history_table',
            columns: [
                {data: 'stud_id', name: 'student'},
                {data: 'student.program.name', name: 'program'},
                {data: 'type', name: 'type'},
                {data: 'added_by', name: 'added_by'},
                {data: 'created_at', name: 'created_at'},
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"}
            ],
            order: [[4, 'desc']]
        });
    }

    //DATATABLES -- END

    //FUNCTIONS -- START

    load();

    /*setInterval(function(){
        load();
    }, 300000);*/

    $(document).on('click', '.refresh_table', function(){
        load();
    });

    //FUNCTIONS -- END
});