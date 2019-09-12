$(document).ready(function(){

    //INITIALIZE -- START

    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    $('.select2').select2();
    
    //INITIALIZE -- END

    //DATATABLES -- START

    function refresh_student_table(){
        $('#student_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_tf_student',
                data: {
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'student.program.name', name: 'program'},
                {data: 'student.contact', name: 'contact'},
                {data: 'balance', name: 'balance'},
                {data: 'sec_bond', name: 'sec_bond'},
                {data: 'class', name: 'class'},
                {data: 'student.status', name: 'status'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}]
        });
    }

    function refresh_program_table(){
        $('#program_table').DataTable({
            initComplete: function(settings, json) {
                //enableTabs();
            },
            processing: true,
            destroy: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_tf_program',
                data: {
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'total', name: 'total'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}]
        });
    }
    
    //DATATABLES -- END

    //FUNCTIONS -- START

    $('.student_pick, .tuition_sec_pick, .program_pick').on('click', function(){
        refresh_program_table();
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });
    
    //FUNCTIONS -- END
});