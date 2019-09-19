$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Student';

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

    $("#projection_modal").on("hidden.bs.modal", function(e){
        $('#projection_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    });

    $("#add_student_modal").on("hidden.bs.modal", function(e){
        add_student_modal_clear();
        $('#expense_continuous').bootstrapToggle('off');
    });

    function add_student_modal_clear(){
        $('#add_student_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#add_student_form').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#add_student_continuous').bootstrapToggle('off');

    function disableTabs(){
        $(`li.student_pick, li.tuition_sec_pick, li.program_pick`
        ).addClass('disabled').css('cursor', 'not-allowed');

        $(`a.student_pick, a.tuition_sec_pick, a.program_pick`
        ).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.student_pick, li.tuition_sec_pick, li.program_pick`
        ).removeClass('disabled').css('cursor', 'pointer');
        
        $(`a.student_pick, a.tuition_sec_pick, a.program_pick`
        ).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh(){
        disableTabs();
        
        if(current_tab == 'Student'){
            refresh_student_table();
        }
        else{
            console.log('mao ni');
            refresh_program_table();
        }
        
        setTimeout(function(){
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 1000);
    }
    
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
                enableTabs();
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

    refresh();
    
    $('.refresh_table').on('click', function(){
        refresh();
    });
    
    //DATATABLES -- END

    //FUNCTIONS -- START
    
    $('.student_pick, .tuition_sec_pick, .program_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_tab = $(this).text();
            refresh();
        }
    });

    $(document).on('click', '.add_student', function(){
        $('#add_student_modal').modal('toggle');
        $('#add_student_modal').modal('show');
    });

    $(document).on('click', '.view_student_tuition', function(){
        let id = $(this).attr('id');

        $.ajax({
            url: '/get_tf_student/'+id,
            method: 'get',
            dataType: 'JSON',
            success:function(data){
                $('#student_tuition_modal .modal-title').text(data.tf_student.student.fname + ' ' + data.tf_student.student.lname);
                $('.tf_balance').text(data.tf_payment);
                $('.tf_sb_total').text(data.sec_bond);
            }
        });

        $('#student_tuition_modal').modal('toggle');
        $('#student_tuition_modal').modal('show');
    });

    $(document).on('click', '.projection', function(){
        let id = $(this).attr('id');

        $('#prog_id').val(id);

        $.ajax({
            url: '/get_tf_projected/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                for(let x = 0; x < data.tf_name_list.length; x++){
                    $($('.proj_name_id')[x]).val(data.tf_name_list[x]);
                    if(data.tf_projected.count != 0){
                        for(let y = 0; y < data.tf_projected.length; y++){
                            if(data.tf_name_list[x] == data.tf_projected[y].tf_name_id){
                                $($('.proj_amount')[x]).val(data.tf_projected[y].amount);
                                $($('.proj_date')[x]).val(data.tf_projected[y].date_of_payment);
                                $($('.proj_remarks')[x]).val(data.tf_projected[y].remarks);
                                break;
                            }
                        }
                    }
                }
            }
        });

        $('#projection_modal').modal('toggle');
        $('#projection_modal').modal('show');
    });

    $(document).on('submit', '#projection_form', function(e){
        e.preventDefault();

        var input = $('.save_projection');
        var button = document.getElementsByClassName("save_projection")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_projection',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#projection_modal').modal('hide');
                refresh();
                button.disabled = false;
                input.html('SAVE CHANGES');
            },
            error: function(data){
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#add_student_form', function(e){
        e.preventDefault();

        var input = $('.save_add_student');
        var button = document.getElementsByClassName("save_add_student")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_tf_student',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#add_student_modal').modal('hide');
                if($('#add_student_continuous').is(':checked')){
                    add_student_modal_clear();
                }
                else{
                    $('#add_student_modal').modal('hide')
                }
                refresh();
                button.disabled = false;
                input.html('SAVE CHANGES');
            },
            error: function(data){
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $('#student').on('change', function(){
        let id = $(this).val();

        if($(this).val() != null){
            $.ajax({
                url: '/get_balance_class/'+id,
                method: 'get',
                dataType: 'JSON',
                success:function(data){
                    $('#balance').val(data.balance);
                    $('#balance').attr('readonly', false);
                    $('#student_class').val(data.class);
                },
            });
        }
        else{
            $('#balance').val('');
            $('#balance').attr('readonly', true);
            $('#student_class').val('');
        }
    });

    //Student Select
    $('#student').select2({
        allowClear: true,
        placeholder: 'Select Student',
        ajax: {
            url: "/t_get_student",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
    });
    
    //FUNCTIONS -- END
});