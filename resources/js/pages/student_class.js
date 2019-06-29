$(document).ready(function(){
    
    //INITIALIZE -- START

    $('.clockpicker').clockpicker();

    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    $('.select2').select2();

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $('input, select').attr('autocomplete', 'off');


    //INITIALIZE -- END
    
    //DATATABLES -- START

    $('#student_class_table').DataTable({stateSave: true,
        stateSaveCallback: function(settings,data) {
            localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        },
        stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        },
        initComplete: function(settings, json) {
        },
        processing: true,
        destroy: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: true,
        responsive: true,
    });

    //DATATABLES -- END
    
    //FUNCTIONS -- START
    
    //Open Student Modal (ADD)
    $('.add_class').on('click', function(){
        $('#add_class_modal').modal('toggle');
        $('#add_class_modal').modal('show');
    });

    //FUNCTIONS -- END
});