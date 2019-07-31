$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Expense'; //Expense, Type, Particular

    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $("#expense_type_modal").on("hidden.bs.modal", function(e){
        $('#expense_type_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });

    function refresh(){
        if(current_tab == 'Expense'){
            
        }
        else if(current_tab == 'Type'){
            refresh_type_table();
        }
        else{//Particular
            refresh_particular_table()
        }
    }

    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    $('#expense_table').DataTable();

    function refresh_type_table(){
        var type_table = $('#type_table').DataTable({
            initComplete: function(settings, json) {
                //enableTabs();  
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_expense_type',
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}],
        });
    }

    function refresh_particular_table(){
        var particular_table = $('#particular_table').DataTable();
    }

    refresh();

    //DATATABLES -- END

    //FUNCTIONS -- START

    $(document).on('click', '.expense_pick', function(){
        current_tab = $(this).text();
    });

    $(document).on('click', '.add_expense', function(){
        if(current_tab == 'Expense'){
            $('#e_add_edit').val('add')
            $('#expense_modal').modal('toggle');
            $('#expense_modal').modal('show');
        }
        else if(current_tab == 'Type'){
            $('#t_add_edit').val('add')
            $('#expense_type_modal').modal('toggle');
            $('#expense_type_modal').modal('show');
        }
        else{//Particular
            $('#p_add_edit').val('add')
            $('#expense_particular_modal').modal('toggle');
            $('#expense_particular_modal').modal('show');
        }
    });

    $(document).on('submit', '#expense_type_form', function(e){
        e.preventDefault();

        var input = $('.save_expense_type');
        var button = document.getElementsByClassName("save_expense_type")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_expense_type',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#expense_type_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //FUNCTIONS -- END

})