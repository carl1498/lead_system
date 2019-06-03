$(document).ready(function(){

    //INITIALIZE -- START

    var current_settings = 'Program';
    var student_settings;

    $('#student_settings_modal').on('shown.bs.modal', function(){
        $('#student_settings_name').focus();
    });

    $("#student_settings_modal").on("hidden.bs.modal", function(e){
        $('#student_settings_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });
    
    $('input, select').attr('autocomplete', 'off');

    //INITIALIZE -- END


    //DATATABLES -- START

    function refresh_student_settings(){
        student_settings = $('#student_settings').DataTable({
            processing: true,
            destroy: true,
            autoWidth: true,
            scrollCollapse: true,
            ajax: {
                url: '/view_student_settings/'+current_settings,
                data: {current_settings: current_settings}
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'action', orderable: false, searchable: false}
            ]
        });
    }

    refresh_student_settings();

    //DATATABLES -- END

    //FUNCTIONS -- START

    //Clicking on tabs
    $('.settings_pick').on('click', function(){
        current_settings = $(this).text();

        refresh_student_settings();
    });

    //save student settings
    $(document).on('submit', '#student_settings_form', function(e){
        e.preventDefault();

        var input = $('.save_student_settings');
        var button = document.getElementsByClassName("save_student_settings")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'json', 
            method: 'POST',
            url: '/save_student_settings',
            data: $(this).serialize() + '&current_settings=' + current_settings,
            success: function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#student_settings_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_student_settings();
            },
            error: function(data){
                swal("Duplicate!", "Data may already exists.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Open Student Settings Modal (ADD)
    $('.add_student_settings').on('click', function(){
        $('#add_edit').val('add');
        $('#student_settings_modal .modal-title').text('Add ' + current_settings);
        $('#student_settings_form label').text(current_settings + ' Name');
        $('#student_settings_name').attr('placeholder', 'Enter ' + current_settings + ' Name');
        
        $('#student_settings_modal').modal('toggle');
        $('#student_settings_modal').modal('show');
    });

    //Open Student Settings Modal (EDIT)
    $(document).on('click', '.edit_student_settings', function(){
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_student_settings',
            method: 'get',
            data: {
                id: id,
                current_settings: current_settings
            },
            dataType: 'text',
            success: function(data){
                $('#id').val(id);
                $('#add_edit').val('edit');

                $('#student_settings_name').val(data);
                
                $('#student_settings_modal .modal-title').text('Add ' + current_settings);
                $('#student_settings_form label').text(current_settings + ' Name');
                $('#student_settings_modal').modal('toggle');
                $('#student_settings_modal').modal('show');
            }
        });
    });

    //Delete Student Settings
    $(document).on('click', '.delete_student_settings', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete this setting. This may affect multiple rows',
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if(result.value){
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/delete_student_settings',
                    method: 'get',
                    data: {
                        id:id,
                        current_settings: current_settings
                    },
                    type: 'json',
                    success:function(data){
                        notif('Success!', 'This Student Setting has been Deleted', 'success', 'glyphicon-ok');
                        refresh_student_settings();
                    }
                })
            }
        });
    });

    //FUNCTIONS -- END
});