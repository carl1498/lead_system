$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Student';
    var add_edit, payment_type;
    var s_modal = false, p_modal = false;

    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    $('.select2').select2();

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    //tuition select init -- Start

    $('.class_select, .program_select, .branch_select, .departure_select').show();
    $(`#class_select, #program_select, #branch_select, #departure_year_select,
        #departure_month_select`).next(".select2-container").show();

    //tuition select init -- End

    $('body').click(function () {
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
        $('#add_student_continuous').bootstrapToggle('off');
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
        if(p_modal == true){
            s_modal = true;
            p_modal = false;
            setTimeout(function(){$('#student_tuition_modal').modal('show')}, 500);
        }
    });
    
    $("#student_tuition_modal").on("hidden.bs.modal", function(e){
        if(s_modal == true){
            s_modal = false;
        }
    });
    
    $("#initial_balance_modal").on("hidden.bs.modal", function(e){
        setTimeout(function(){$('#student_tuition_modal').modal('show')}, 500);
    });

    function tf_sb_payment_clear(){
        $('#tf_sb_payment_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#tf_sb_payment_form').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
        $('#p_student').attr('disabled', false);
        $('#sign_up_check').prop('checked', false).attr('disabled', true);
        $('#sign_up').val(0);
    }

    $('#tf_sb_payment_continuous').bootstrapToggle('off');

    function disableTabs(){
        $(`li.student_pick, li.tuition_sec_pick, li.program_pick, li.tf_breakdown_pick`
        ).addClass('disabled').css('cursor', 'not-allowed');

        $(`a.student_pick, a.tuition_sec_pick, a.program_pick, a.tf_breakdown_pick`
        ).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.student_pick, li.tuition_sec_pick, li.program_pick, li.tf_breakdown_pick`
        ).removeClass('disabled').css('cursor', 'pointer');
        
        $(`a.student_pick, a.tuition_sec_pick, a.program_pick, a.tf_breakdown_pick`
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
        else if(current_tab == 'TF Breakdown'){
            refresh_tf_breakdown_table();
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
        
        class_select = $('#class_select').val();
        program_select = $('#program_select').val();
        branch_select = $('#branch_select').val();
        departure_year_select = $('#departure_year_select').val();
        departure_month_select = $('#departure_month_select').val();

        $('#student_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            responsive: true,
            ajax: {
                url: '/view_tf_student',
                data: {
                    class_select:class_select,
                    program_select:program_select,
                    branch_select:branch_select,
                    departure_year_select:departure_year_select,
                    departure_month_select:departure_month_select,
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'student.program.name', name: 'program'},
                {data: 'student.branch.name', name: 'branch'},
                {data: 'student.contact', name: 'contact'},
                {data: 'balance', name: 'balance'},
                {data: 'sec_bond', name: 'sec_bond'},
                {data: 'class', name: 'class'},
                {data: 'student.status', name: 'status'},
                {data: 'departure', name: 'departure'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [4,5]}
            ],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                total = api
                    .column( 4 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Total over all pages
                total2 = api
                    .column( 5 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
     
                // Update footer
                $( api.column( 4 ).footer() ).html(
                    '₱' + total.toFixed(2)
                );
     
                // Update footer
                $( api.column( 5 ).footer() ).html(
                    '₱' + total2.toFixed(2)
                );
            }
        });
    }

    function refresh_tuition_sec_table(){
        
        class_select = $('#class_select').val();
        program_select = $('#program_select').val();
        branch_select = $('#branch_select').val();
        departure_year_select = $('#departure_year_select').val();
        departure_month_select = $('#departure_month_select').val();

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
                    current_tab:current_tab,
                    class_select:class_select,
                    program_select:program_select,
                    branch_select:branch_select,
                    departure_year_select:departure_year_select,
                    departure_month_select:departure_month_select,
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'student.student.program.name', name: 'program'},
                {data: 'student.student.branch.name', name: 'branch'},
                {data: 'amount', name: 'amount'},
                {data: 'date', name: 'date'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [3]}
            ],
            order: [4, 'desc'],
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
     
                // Remove the formatting to get integer data for summation
                var intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
     
                // Total over all pages
                total = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 3 ).footer() ).html(
                    '₱' + total.toFixed(2)
                );
            }
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
                {data: 'remarks', name: 'remarks'},
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
                {data: 'remarks', name: 'remarks'},
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
    
    $('.student_pick, .tuition_sec_pick, .program_pick, .tf_breakdown_pick').on('click', function(){
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
        payment_type = 'sec_bond';
        tf_sb_payment_init('add', 'sec_bond', 'Security Bond Payment');
    });

    $(document).on('click', '.edit_sb_payment', function(){
        let id = $(this).attr('id');
        payment_type = 'sec_bond';
        $('#sign_up').val(0);
        get_tf_sb_payment(id, 'sec_bond', 'Security Bond Payment');
    });

    $(document).on('click', '.tf_payment', function(){
        payment_type = 'tuition';
        $('#sign_up').val(0);
        tf_sb_payment_init('add', 'tuition', 'Tuition Fee Payment');
    });

    $(document).on('click', '.edit_tf_payment', function(){
        let id = $(this).attr('id');
        payment_type = 'tuition';
        get_tf_sb_payment(id, 'tuition', 'Tuition Fee Payment');
    });

    $(document).on('click', '.edit_initial_balance', function(){
        let id = $(this).attr('id');

        $.ajax({
            url: '/get_initial_balance/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#i_id').val(data.id);
                $('#initial_balance_modal .modal-title').text(data.student.fname + ' ' + data.student.lname);
                $('#init_balance').val(data.balance);
                $('#student_tuition_modal').modal('hide');
                setTimeout(function(){$('#initial_balance_modal').modal('show')}, 500);
            }
        });
    });

    function get_tf_sb_payment(id, p_type, payment){
        $.ajax({
            url: '/get_tf_sb_payment',
            data: {
                id:id,
                p_type:p_type
            },
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#p_id').val(data.id);
                $('#p_student').attr('disabled', true);
                $('#p_student').val(data.tf_stud_id).trigger('change');
                $('#p_amount, #p_prev_amount').val(data.amount).trigger('change');
                $('#date').val(data.date);
                $('#remarks').val(data.remarks);
                if(data.sign_up == 1){
                    $('#sign_up_check').attr('disabled', false).prop('checked', true);
                    $('#sign_up').val(1);
                }
                else{
                    $('#sign_up').val(0);
                }
            }
        });

        tf_sb_payment_init('edit', p_type, payment);
    }

    function tf_sb_payment_init(add_edit, p_type, payment){
        $('#tf_sb_payment_modal .modal-title').text(payment);
        $('#add_edit').val(add_edit);
        $('#p_type').val(p_type);

        if(s_modal == true){
            p_modal = true;
            $('#student_tuition_modal').modal('hide');
            setTimeout(function(){$('#tf_sb_payment_modal').modal('show')}, 500);
        }
        else{
            $('#tf_sb_payment_modal').modal('show')
        }
    }

    $(document).on('click', '.view_student_tuition', function(){
        let id = $(this).attr('id');

        refresh_view_student_tuition(id);
    });

    function refresh_view_student_tuition(id){
        $.ajax({
            url: '/get_student_tuition/'+id,
            method: 'get',
            dataType: 'JSON',
            success:function(data){
                refresh_tuition_sec_bond_table(data.tf_student.id);
                $('.edit_initial_balance').attr('id', data.tf_student.id);
                $('#student_tuition_modal .modal-title').text(data.tf_student.student.fname + ' ' + data.tf_student.student.lname);
                $('.current_class').text(data.class);
                $('.tf_balance').text(data.tf_payment.toFixed(2));
                $('.tf_sign_up').text(data.tf_sign_up);
                $('.tf_sb_total').text(data.sec_bond);
                s_modal = true;

                $('#student_tuition_modal').modal('show');
            }
        });
    }

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
                    $('#add_edit').val('add');
                    $('#p_type').val(payment_type);
                }
                else{
                    if(p_modal == true){
                        refresh_view_student_tuition($('#p_student').val());
                        refresh_tuition_sec_bond_table($('#p_student').val());
                    }
                    $('#tf_sb_payment_modal').modal('hide');
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

    $(document).on('submit', '#initial_balance_form', function(e){
        e.preventDefault();

        var input = $('.save_initial_balance');
        var button = document.getElementsByClassName("save_initial_balance")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_initial_balance',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                refresh_view_student_tuition($('#i_id').val())
                refresh_tuition_sec_bond_table($('#i_id').val());
                refresh();
                $('#initial_balance_modal').modal('hide');
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
    
    $('#sign_up_check').on('change', function(){
        ($(this).is(':checked')) ? $('#sign_up').val(1) :  $('#sign_up').val(0);
        $('#p_student').trigger('change');
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

    $('#p_student').on('change', function(){
        let id = $(this).val();

        if($(this).val() != null){
            $.ajax({
                url: '/get_student_tuition/'+id,
                method: 'get',
                dataType: 'JSON',
                success:function(data){
                    if($('#sign_up_check').is(':checked')){
                        $('#current, #total').val(data.tf_sign_up);
                    }
                    else if($('#p_type').val() == 'tuition'){
                        $('#current, #total').val(data.tf_payment);
                        $('#sign_up_check').attr('disabled', false);
                    }
                    else if($('#p_type').val() == 'sec_bond'){
                        $('#current, #total').val(data.sec_bond);
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

    $(`#class_select, #branch_select, #program_select, #departure_year_select, 
        #departure_month_select`).on('change', function(){
        if($(this).attr('id') == "class_select"){
            $('#class_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "program_select"){
            $('#program_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "branch_select"){
            $('#branch_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "departure_year_select"){
            $('#year_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "departure_month_select"){
            $('#month_hidden').val($(this).val());
        }

        refresh();
    });

    $('#p_amount').on('keyup change', function(){
        let current = $('#current').val();
        let amount = $('#p_amount').val();
        let prev_amount = $('#p_prev_amount').val();
        let p_type = $('#p_type').val();

        if($('#sign_up_check').is(':checked')){
            if(prev_amount != ''){
                amount = parseFloat(amount) - parseFloat(prev_amount);
            }
            $('#total').val(parseFloat(current) + parseFloat(amount));
        }
        if(p_type == 'tuition'){
            if(prev_amount != ''){
                amount = parseFloat(amount) - parseFloat(prev_amount);
            }
            $('#total').val(parseFloat(current) - parseFloat(amount));
        }
        else if(p_type == 'sec_bond'){
            if(prev_amount != ''){
                amount = parseFloat(amount) - parseFloat(prev_amount);
            }
            $('#total').val(parseFloat(current) + parseFloat(amount));
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

    function refresh_tf_breakdown_table(){
        
        class_select = $('#class_select').val();
        program_select = $('#program_select').val();
        branch_select = $('#branch_select').val();
        departure_year_select = $('#departure_year_select').val();
        departure_month_select = $('#departure_month_select').val();

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/view_tf_breakdown',
            data: {
                class_select:class_select,
                program_select:program_select,
                branch_select:branch_select,
                departure_year_select:departure_year_select,
                departure_month_select:departure_month_select,
            },
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#tf_breakdown_table').empty();

                let html = '';
                let installment = '';
                let i_amount_date = '';
                let i_payment = '';

                html += '<thead>';

                for(let x = 0; x < data.installment; x++){

                    installment += '<th colspan="2" class="installments">Installment ' + (x+1) + '</th>';
                    i_amount_date += '<th>Amount Paid</th><th>Payment Date</th>';
                }
                i_amount_date += '<th>Amount Paid</th><th>Payment Date</th>';

                html += `
                <tr>
                    <th rowspan="2" style="width: 300px;">Name</th>
                    <th rowspan="2" style="width: 150px;">Program</th>
                    <th colspan="2" style="width: 250px;">Prof Fee</th>
                    <th rowspan="2" style="width: 150px;">Total Tuition</th>
                    <th rowspan="2" style="width: 150px;">Total Payment</th>`
                    +installment+
                    `<th rowspan="2" style="width: 100px;">Balance</th>
                </tr>
                <tr>`
                    +i_amount_date;

                html += '</thead>';

                html += '<tbody>';

                for(let x = 0; x < data.tf_student.length; x++){
                    let mname = (data.tf_student[x].student.mname) ? data.tf_student[x].student.mname : '';
                    let i_payment = '';

                    for(let y = 0; y < data.installment; y++){
                        let amount = 0;
                        let date = '';
                        if(data.tf_student[x].payment[y]){
                            amount = data.tf_student[x].payment[y].amount;
                            date = data.tf_student[x].payment[y].date;
                        }
                        i_payment += '<td style="text-align:right;">' + amount + '</td>' +
                            '<td style="text-align:center;">' + date + '</td>';
                    }

                    html += `<tr>
                        <td>` + 
                        data.tf_student[x].student.lname + ', ' + data.tf_student[x].student.fname + ' ' + mname +
                        '</td>' +
                        '<td>' + data.tf_student[x].student.program.name + '</td>' +
                        '<td style="text-align:right;">' + data.tf_student[x].prof_fee + '</td>' +
                        '<td style="text-align:center;">' + data.tf_student[x].prof_fee_date + '</td>' +
                        '<td style="text-align:right;">' + data.tf_student[x].balance + '</td>' +
                        '<td style="text-align:right;">' + data.tf_student[x].total_payment + '</td>' +
                        i_payment +
                        '<td style="text-align:right;">' + data.tf_student[x].remaining_bal + '</td>' +
                    '</tr>'
                }

                html += '</tbody>';

                html += '<tfoot>';

                let footer_installment = '';

                for(let x = 0; x < data.installment; x++){
                    footer_installment += '<td style="text-align:right;">' + data.footer.installment[x] + '</td><td></td>';
                }

                html += `<tr>
                    <td style="text-align:center;" colspan="2">TOTAL</td>
                    <td style="text-align:right;">` + data.footer.sign_up + `</td>
                    <td></td>
                    <td style="text-align:right;">` + data.footer.total_tuition + `</td>
                    <td style="text-align:right;">` + data.footer.total_payment + `</td>` +
                    footer_installment +
                    '<td style="text-align:right;">' + data.footer.balance + '</td>';

                html += '</tfoot>';

                $('#tf_breakdown_table').append(html);
                
                enableTabs();
            }
        });
    }    
    
    //FUNCTIONS -- END
});