$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Student';
    var s_modal = false, p_modal = false;
    var title;

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

    $('#tf_payment_modal').on("hidden.bs.modal", function(e){
        tf_payment_clear();
        $('#tf_payment_continuous').bootstrapToggle('off');
        if(p_modal == true){
            s_modal = true;
            p_modal = false;
            setTimeout(function(){$('#student_tuition_modal').modal('show')}, 500);
        }
    });

    function tf_payment_clear(){
        $('#tf_payment_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#tf_payment_form').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#sb_payment_modal').on("hidden.bs.modal", function(e){
        sb_payment_clear();
        $('#sb_payment_continuous').bootstrapToggle('off');
        if(p_modal == true){
            s_modal = true;
            p_modal = false;
            setTimeout(function(){$('#student_tuition_modal').modal('show')}, 500);
        }
    });

    function sb_payment_clear(){
        $('#sb_payment_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#sb_payment_form').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#tf_payment_continuous').bootstrapToggle('off');
    $('#sb_payment_continuous').bootstrapToggle('off');

    function disableTabs(){
        $(`li.student_pick, li.payment_pick, li.sec_bond_pick, li.program_pick, 
        li.tf_breakdown_pick, li.summary_pick, li.soa_pick`
        ).addClass('disabled').css('cursor', 'not-allowed');

        $(`a.student_pick, a.payment_pick, a.sec_bond_pick, a.program_pick, 
        a.tf_breakdown_pick, a.summary_pick, a.soa_pick`
        ).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.student_pick, li.payment_pick, li.sec_bond_pick, li.program_pick, 
        li.tf_breakdown_pick, li.summary_pick, li.soa_pick`
        ).removeClass('disabled').css('cursor', 'pointer');
        
        $(`a.student_pick, a.payment_pick, a.sec_bond_pick, a.program_pick, 
        a.tf_breakdown_pick, a.summary_pick, a.soa_pick`
        ).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh(){
        disableTabs();
        get_year = $("#departure_year_select option:selected").text();
        get_month = $("#departure_month_select option:selected").text();
        update_buttons();
        
        if(current_tab == 'Student'){
            refresh_student_table();
        }
        else if(current_tab == 'Programs'){
            refresh_program_table();
        }
        else if(current_tab == 'Payment History'){
            refresh_payment_table();
        }
        else if(current_tab == 'Security Bond History'){
            refresh_sec_bond_table();
        }
        else if(current_tab == 'TF Breakdown'){
            refresh_tf_breakdown_table();
        }
        else if(current_tab == 'Summary'){
            refresh_summary_table();
        }
        else if(current_tab == 'SOA'){
            refresh_soa_table();
        }
        
    }
    
    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function update_buttons(){
        
        switch(current_tab){
            case 'Payment History'  :   title = ' DEPARTURE PAYMENT HISTORY'; break;
            case 'Security Bond History'  :   title = ' DEPARTURE SECURITY BOND'; break;
        }

        get_departure = (get_year == 'All' && get_month == 'All') ? 'All' : get_year + ' ' + get_month;

        payment_buttons_format = [
            {extend: 'excelHtml5', title: get_departure + title,
            exportOptions: {
                columns: ':visible'
            }},
            'colvis'
        ];
    }

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
            dom: 'Bflrtip',
            buttons: payment_buttons_format,
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
                {data: 'program.name', name: 'program'},
                {data: 'branch.name', name: 'branch'},
                {data: 'contact', name: 'contact'},
                {data: 'balance', name: 'balance'},
                {data: 'sec_bond', name: 'sec_bond'},
                {data: 'class', name: 'class'},
                {data: 'status', name: 'status'},
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

    function refresh_payment_table(){
        
        class_select = $('#class_select').val();
        program_select = $('#program_select').val();
        branch_select = $('#branch_select').val();
        departure_year_select = $('#departure_year_select').val();
        departure_month_select = $('#departure_month_select').val();

        $('#payment_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();
            },
            dom: 'Bflrtip',
            buttons: payment_buttons_format,
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_tf_payment',
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
                {data: 'tf_name.name', name: 'tf_name'},
                {data: 'student.program.name', name: 'program'},
                {data: 'student.branch.name', name: 'branch'},
                {data: 'amount', name: 'amount'},
                {data: 'date', name: 'date'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [4]}
            ],
            order: [5, 'desc'],
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
     
                // Update footer
                $( api.column( 4 ).footer() ).html(
                    '₱' + total.toFixed(2)
                );
            }
        });
    }

    function refresh_sec_bond_table(){
        
        class_select = $('#class_select').val();
        program_select = $('#program_select').val();
        branch_select = $('#branch_select').val();
        departure_year_select = $('#departure_year_select').val();
        departure_month_select = $('#departure_month_select').val();

        $('#sec_bond_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();
            },
            dom: 'Bflrtip',
            buttons: payment_buttons_format,
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_sec_bond',
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

    function refresh_tf_modal_tables(id){
        $('#tf_modal_table').DataTable({
            paging: false,
            info: false,
            searching: false,
            processing: true,
            destroy: true,
            responsive: true,
            ajax: {
                url: '/view_tf_modal/'+id,
                data: {
                }
            },
            columns: [
                {data: 'amount', name: 'amount'},
                {data: 'tf_name.name', name: 'tf_name.name'},
                {data: 'date', name: 'date'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
            ],
            order: [2, 'desc']
        });

        $('#sb_modal_table').DataTable({
            paging: false,
            info: false,
            searching: false,
            processing: true,
            destroy: true,
            responsive: true,
            ajax: {
                url: '/view_sb_modal/'+id,
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
            ],
            order: [1, 'desc']
        });
    }

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

                for(let x = 0; x < data.student.length; x++){
                    let mname = (data.student[x].mname) ? data.student[x].mname : '';
                    let program = (data.student[x].program) ? data.student[x].program.name : '';
                    let i_payment = '';

                    for(let y = 0; y < data.installment; y++){
                        let amount = 0;
                        let date = '';
                        
                        if(data.student[x].payment[y]){
                            amount = data.student[x].payment[y].amount;
                            date = data.student[x].payment[y].date;
                        }
                        i_payment += '<td style="text-align:right;">' + amount + '</td>' +
                            '<td style="text-align:center;">' + date + '</td>';
                    }

                    html += `<tr>
                        <td>` + 
                        data.student[x].lname + ', ' + data.student[x].fname + ' ' + mname +
                        '</td>' +
                        '<td>' + program + '</td>' +
                        '<td style="text-align:right;">' + data.student[x].prof_fee + '</td>' +
                        '<td style="text-align:center;">' + data.student[x].prof_fee_date + '</td>' +
                        '<td style="text-align:right;">' + data.student[x].balance + '</td>' +
                        '<td style="text-align:right;">' + data.student[x].total_payment + '</td>' +
                        i_payment +
                        '<td style="text-align:right;">' + data.student[x].remaining_bal + '</td>' +
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
    
    function refresh_summary_table(){
        
        class_select = $('#class_select').val();
        program_select = $('#program_select').val();
        branch_select = $('#branch_select').val();
        departure_year_select = $('#departure_year_select').val();
        departure_month_select = $('#departure_month_select').val();

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/view_summary',
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
                $('#summary_table').empty();
                
                let html = '';

                html += '<thead>';

                html += `
                <tr>
                    <th style="width: 300px;">Name</th>
                    <th style="width: 150px;">Program</th>
                    <th style="width: 150px;">School</th>
                    <th style="width: 150px;">Sec Bond Amount</th>
                    <th style="width: 200px;">Tuition Fee / Sign Up Fee</th>
                    <th style="width: 100px;">VISA Fee</th>
                    <th style="width: 100px;">PDOS</th>
                    <th style="width: 120px;">Selection Fee</th>
                    <th style="width: 100px;">Airfare</th>
                    <th style="width: 100px;">DHL</th>
                    <th style="width: 100px;">BC&ITR</th>
                </tr>`

                html += '</thead>';

                html += '<tbody>';

                for(let x = 0; x < data.length; x++){
                    let mname = (data[x].mname) ? data[x].mname : '';
                    let program = (data[x].program) ? data[x].program.name : '';
                    let student = (data[x].school) ? data[x].school.name : '';

                    html += `<tr>
                    <td>` +
                    data[x].lname + ', ' + data[x].fname + ' ' + mname +
                    '</td>' +
                    '<td>' + program + '</td>' +
                    '<td>' + student + '</td>' +
                    '<td style="text-align:right;">' + data[x].sec_bond + '</td>' +
                    '<td style="text-align:right;">' + data[x].tf_su + '</td>' +
                    '<td style="text-align:right;">' + data[x].visa + '</td>' +
                    '<td style="text-align:right;">' + data[x].pdos + '</td>' +
                    '<td style="text-align:right;">' + data[x].select + '</td>' +
                    '<td style="text-align:right;">' + data[x].air + '</td>' +
                    '<td style="text-align:right;">' + data[x].dhl + '</td>' +
                    '<td style="text-align:right;">' + data[x].docu + '</td>' +
                    '</td>'
                }

                html += '</tbody>';

                $('#summary_table').append(html);

                enableTabs();
            }
        })

    }

    function refresh_soa_table(){
        $('#soa_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_soa',
                data: {
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'batch', name: 'batch'},
                {data: 'due', name: 'due'},
                {data: 'paid', name: 'paid'},
                {data: 'balance', name: 'balance'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                //{className: "text-right", targets: [4]}
            ],
        });
    }

    refresh();
    
    $('.refresh_table').on('click', function(){
        refresh();
    });
    
    //DATATABLES -- END

    //FUNCTIONS -- START
    
    $(`.student_pick, .payment_pick, .sec_bond_pick, .program_pick, 
        .tf_breakdown_pick, .summary_pick, .soa_pick`).on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_tab = $(this).text();
            refresh();
        }
    });

    $(document).on('click', '.tf_payment', function(){
        $('#p_add_edit').val('add');
        $('#tf_payment_modal').modal('show');
    });

    $(document).on('click', '.sb_payment', function(){
        $('#s_add_edit').val('add');
        $('#sb_payment_modal').modal('show');
    });
    
    $(document).on('click', '.edit_tf_payment', function(){
        let id = $(this).attr('id');

        $.ajax({
            url: '/get_tf_payment/'+id,
            method: 'get',
            dataType: 'JSON',
            success:function(data){
                $('#p_id').val(data.id);
                $('#p_add_edit').val('edit');
                $('#p_student').val(data.stud_id).trigger('change');
                $('#type').val(data.tf_name_id).trigger('change');
                $('#p_amount').val(data.amount);
                $('#p_date').val(data.date);
                $('#p_remarks').val(data.remarks);
                if(s_modal == true){
                    p_modal = true;
                    $('#student_tuition_modal').modal('hide');
                    setTimeout(function(){$('#tf_payment_modal').modal('show')}, 500);
                }else{$('#tf_payment_modal').modal('show');}
            }
        });
    });
    
    $(document).on('click', '.edit_sb_payment', function(){
        let id = $(this).attr('id');

        $.ajax({
            url: '/get_sb_payment/'+id,
            method: 'get',
            dataType: 'JSON',
            success:function(data){
                $('#s_id').val(data.id);
                $('#s_add_edit').val('edit');
                $('#s_student').val(data.stud_id).trigger('change');
                $('#s_amount').val(data.amount);
                $('#s_date').val(data.date);
                $('#s_remarks').val(data.remarks);
                if(s_modal == true){
                    p_modal = true;
                    $('#student_tuition_modal').modal('hide');
                    setTimeout(function(){$('#sb_payment_modal').modal('show')}, 500);
                }else{$('#sb_payment_modal').modal('show');}
            }
        });
    });

    $(document).on('click', '.view_tf_student_modal', function(){
        let id = $(this).attr('id');
        view_tf_student_modal(id);
        $('#student_tuition_modal').modal('show');
    });

    function view_tf_student_modal(id){
        $.ajax({
            url: '/view_tf_student_modal/'+id,
            method: 'get',
            dataType: 'JSON',
            success:function(data){
                $('#student_tuition_modal .modal-title').text(data.student.lname + ', ' + data.student.fname + ' ' +
                ((data.student.mname) ? data.student.mname : ''));
                $('.current_class').text(data.class_students);
                for(let x = 0; x <= Object.keys(data.tf).length; x++){
                    $($('.pay')[x]).text(data.tf[x]);
                }
                if(data.tf_projected.length != 0){
                    for(let x = 0; x < data.tf_projected.length; x++){
                        $($('.bal')[x]).text(data.tf_projected[x].amount);
                    }
                    $($('.bal')[8]).text(data.tp_total);
                }else{
                    for(let x = 0; x < Object.keys(data.tf).length+1; x++){
                        $($('.bal')[x]).text(0);
                    }
                }
                $('.sb_total').text(data.sb_total);                
                refresh_tf_modal_tables(data.student.id);
                
                s_modal = true;
            }
        });
    }
    
    $(document).on('click', '.delete_tf_payment', function(){
        let id = $(this).attr('id');

        swal.fire({
            title: 'Confirm User',
            text: 'For security purposes, input your password again.',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/confirm_user',
                    data: { password:password },
                    method: 'POST',
                    success: function(data){
                        if(data == 0){
                            swal('Password Incorrect!', 'Please try again', 'error');
                            return;
                        }
                        else{
                            swal({
                                title: 'Warning',
                                text: 'Are you sure?',
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
                                        url: '/delete_tf_payment',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        method: 'get',
                                        type: 'json',
                                        success:function(data){
                                            notif('Success!', 'This Payment has been Deleted', 'success', 'glyphicon-ok');
                                            if(s_modal == true){
                                                view_tf_student_modal(data);
                                            }
                                            refresh();
                                        }
                                    })
                                }
                            });
                        }
                    }
                });
            },
        });
    });

    $(document).on('click', '.delete_sb_payment', function(){
        let id = $(this).attr('id');

        swal.fire({
            title: 'Confirm User',
            text: 'For security purposes, input your password again.',
            input: 'password',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            showLoaderOnConfirm: true,
            preConfirm: (password) => {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/confirm_user',
                    data: { password:password },
                    method: 'POST',
                    success: function(data){
                        if(data == 0){
                            swal('Password Incorrect!', 'Please try again', 'error');
                            return;
                        }
                        else{
                            swal({
                                title: 'Warning',
                                text: 'Are you sure?',
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
                                        url: '/delete_sb_payment',
                                        data: {
                                            id: id,
                                            password: password
                                        },
                                        method: 'get',
                                        type: 'json',
                                        success:function(data){
                                            notif('Success!', 'This Payment has been Deleted', 'success', 'glyphicon-ok');
                                            if(s_modal == true){
                                                view_tf_student_modal(data);
                                            }
                                            refresh();
                                        }
                                    })
                                }
                            });
                        }
                    }
                });
            },
        });
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
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#tf_payment_form', function(e){
        e.preventDefault();

        var input = $('.save_tf_payment');
        var button = document.getElementsByClassName("save_tf_payment")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_tf_payment',
            method: 'POST',
            data: $(this).serialize(),
            success: function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                if($('#tf_payment_continuous').is(':checked')){
                    tf_payment_clear();
                    $('#p_add_edit').val('add');
                }
                else{
                    if(p_modal == true){
                        let stud = $('#p_student').val()
                        view_tf_student_modal(stud);
                    }
                    $('#tf_payment_modal').modal('hide');
                }
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(document).on('submit', '#sb_payment_form', function(e){
        e.preventDefault();

        var input = $('.save_sb_payment');
        var button = document.getElementsByClassName("save_sb_payment")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_sb_payment',
            method: 'POST',
            data: $(this).serialize(),
            success: function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                if($('#sb_payment_continuous').is(':checked')){
                    sb_payment_clear();
                    $('#s_add_edit').val('add');
                }
                else{
                    if(p_modal == true){
                        let stud = $('#s_student').val()
                        view_tf_student_modal(stud);
                    }
                    $('#sb_payment_modal').modal('hide');
                }
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    $(`#class_select, #branch_select, #program_select, #departure_year_select, 
        #departure_month_select`).on('change', function(){
        if($(this).attr('id') == "class_select"){
            $('.class_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "program_select"){
            $('.program_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "branch_select"){
            $('.branch_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "departure_year_select"){
            $('.year_hidden').val($(this).val());
        }
        else if($(this).attr('id') == "departure_month_select"){
            $('.month_hidden').val($(this).val());
        }

        refresh();
    });

    //Student Select TF SB
    $('#p_student, #s_student').select2({
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