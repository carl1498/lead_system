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