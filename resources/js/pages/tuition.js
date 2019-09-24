$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Student';
    var add_edit, p_type;

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

    $("#tf_sb_payment_modal").on("hidden.bs.modal", function(e){
        tf_sb_payment_clear();
        $('#tf_sb_payment_continuous').bootstrapToggle('off');
    });

    function tf_sb_payment_clear(){
        $('#tf_sb_payment_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#tf_sb_payment_form').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#tf_sb_payment_continuous').bootstrapToggle('off');

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
        else if(current_tab == 'Programs'){
            refresh_program_table();
        }
        else{
            refresh_tuition_sec_table();
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
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [3,4]}
            ],
        });
    }

    function refresh_tuition_sec_table(){
        $('#tuition_sec_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_tuition_sec',
                data: {
                    current_tab:current_tab
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'student.student.program.name', name: 'program'},
                {data: 'class', name: 'class'},
                {data: 'amount', name: 'amount'},
                {data: 'date', name: 'date'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [3]}
            ],
            order: [4, 'desc']
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
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [1]}
            ],
        });
    }

    function refresh_tuition_sec_bond_table(id){
        $('#tuition_fee_table').DataTable({
            paging: false,
            info: false,
            searching: false,
            processing: true,
            destroy: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_tuition_fee/'+id,
                data: {
                }
            },
            columns: [
                {data: 'amount', name: 'amount'},
                {data: 'date', name: 'date'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [0]}
            ],
            order: [1, 'desc']
        });
        
        $('#sec_bond_table').DataTable({
            paging: false,
            info: false,
            searching: false,
            processing: true,
            destroy: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_sec_bond/'+id,
                data: {
                }
            },
            columns: [
                {data: 'amount', name: 'amount'},
                {data: 'date', name: 'date'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [0]}
            ],
            order: [1, 'desc']
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

    $(document).on('click', '.sb_payment', function(){
        $('#tf_sb_payment_modal .modal-title').text('Security Bond Payment');
        add_edit = 'add';
        p_type = 'sec_bond';
        add_edit_p_type(add_edit, p_type);

        $('#tf_sb_payment_modal').modal('toggle');
        $('#tf_sb_payment_modal').modal('show');
    });

    $(document).on('click', '.tf_payment', function(){
        tf_payment_init();
    });

    $(document).on('click', '.edit_tf_payment', function(){
        let id = $(this).attr('id');

        $.ajax({
            url: '/get_tf_payment/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#p_id').val(data.id);
                $('#p_student').attr('disabled', true);
                $('#p_student').val(data.tf_stud_id).trigger('change');
                $('#p_amount').val(data.amount).trigger('change');
            }
        });

        tf_payment_init();
    });

    function tf_payment_init(){
        $('#tf_sb_payment_modal .modal-title').text('Tuition Fee Payment');
        add_edit = 'edit';
        p_type = 'tuition';
        add_edit_p_type(add_edit, p_type);
        $('#tf_sb_payment_modal').modal('toggle');
        $('#tf_sb_payment_modal').modal('show');
    }

    function add_edit_p_type(add_edit, p_type){
        $('#add_edit').val(add_edit);
        $('#p_type').val(p_type);
    }

    $(document).on('click', '.view_student_tuition', function(){
        let id = $(this).attr('id');

        $.ajax({
            url: '/get_student_tuition/'+id,
            method: 'get',
            dataType: 'JSON',
            success:function(data){
                refresh_tuition_sec_bond_table(data.tf_student.id);
                $('#student_tuition_modal .modal-title').text(data.tf_student.student.fname + ' ' + data.tf_student.student.lname);
                $('.current_class').text(data.class);
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

    $(document).on('submit', '#tf_sb_payment_form', function(e){
        e.preventDefault();

        var input = $('.save_tf_sb_payment');
        var button = document.getElementsByClassName("save_tf_sb_payment")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_tf_sb_payment',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                if($('#tf_sb_payment_continuous').is(':checked')){
                    tf_sb_payment_clear();
                    add_edit_p_type(add_edit, p_type);
                }
                else{
                    $('#tf_sb_payment_modal').modal('hide')
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
    })

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

    $('#p_student').on('change', function(){
        let id = $(this).val();

        if($(this).val() != null){
            $.ajax({
                url: '/get_student_tuition/'+id,
                method: 'get',
                dataType: 'JSON',
                success:function(data){
                    if($('#p_type').val() == 'tuition'){
                        $('#current').val(data.tf_payment);
                        $('#total').val(($('#p_amount').val() != '') ? data.tf_payment - $('#p_amount').val() : data.tf_payment);
                    }
                    else if($('#p_type').val() == 'sec_bond'){
                        $('#current').val(data.sec_bond);
                        $('#total').val(data.sec_bond);
                        $('#total').val(($('#p_amount').val() != '') ? data.sec_bond + $('#p_amount').val() : data.sec_bond);
                    }
                    $('#p_amount').attr('readonly', false);
                }
            });
        }
        else{
            $('#current, #total, #p_amount, #date, #remarks').val('');
            $('#p_amount').attr('readonly', true);
        }
    });

    $('#p_amount').on('keyup change', function(){
        if($('#p_type').val() == 'tuition'){
            $('#total').val(parseFloat($('#current').val()) - parseFloat($(this).val()))
        }
        else if($('#p_type').val() == 'sec_bond'){
            $('#total').val(parseFloat($('#current').val()) + parseFloat($(this).val()))
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

    //Student Select TF SB
    $('#p_student').select2({
        allowClear: true,
        placeholder: 'Select Student',
        ajax: {
            url: "/get_tf_student",
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