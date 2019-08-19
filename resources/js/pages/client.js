$(document).ready(function(){
  
    //INITIALIZE -- START

    var current_tab = 'Japanese School'; //Japanese School, Medical Facility, Manpower Agency
    
    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });
    
    function disableTabs(){
        $(`li.client_pick`).addClass('disabled').css('cursor', 'not-allowed');

        $(`a.client_pick`).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.client_pick`).removeClass('disabled').css('cursor', 'pointer');

        $(`a.client_pick`).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh(){
        disableTabs();
        refresh_client_table();
    }
    
    $('.refresh_table').on('click', function(){
        refresh();
    });


    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function refresh_client_table(){
        $('#client_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();  
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_client',
                data: {
                    current_tab: current_tab
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'address', name: 'address'},
                {data: 'contact', name: 'contact'},
                {data: 'email', name: 'email'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}]
        });
    }

    refresh();
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

    //DATATABLES -- END

    //FUNCTIONS -- START

    $(document).on('click', '.client_pick', function(){
        current_tab = $(this).text();
    });

    $(document).on('click', '.add_client', function(){
        $('#add_edit').val('add');
        $('#client_modal').modal('toggle');
        $('#client_modal').modal('show');
    });

    $(document).on('click', '.edit_client', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_client/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#id').val(data.id);
                $('#add_edit').val('edit');
                $('#company_type').val(data.client_company_type_id).trigger('change');
                $('#client_name').val(data.name);
                $('#client_address').val(data.address);
                $('#client_contact').val(data.contact);
                $('#client_email').val(data.email);
                $('#client_modal').modal('show');
            }
        })
    })

    $(document).on('submit', '#client_form', function(e){
        e.preventDefault();

        var input = $('.save_client');
        var button = document.getElementsByClassName("save_client")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_client',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#client_modal').modal('hide');
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