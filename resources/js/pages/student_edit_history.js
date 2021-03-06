$(document).ready(function(){

    //INITIALIZE -- START

    //INITIALIZE -- END

    //DATATABLES -- START

    function load(){
        var student_edit_history = $('#student_edit_history').DataTable({
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
            ajax: '/student_edit_history_table',
            columns: [
                {data: 'stud_id', name: 'student'},
                {data: 'field', name: 'field'},
                {data: 'previous', name: 'previous'},
                {data: 'new', name: 'new'},
                {data: 'edited_by', name: 'edited_by'},
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

    /*setInterval(function(){
        load();
    }, 300000);*/

    $(document).on('click', '.refresh_table', function(){
        load();
    });

    //FUNCTIONS -- END
});