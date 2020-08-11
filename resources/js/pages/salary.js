const { indexOf } = require("lodash");

$(document).ready(function(){
    
    //INITIALIZE -- START

    var current_tab = 'Employee';
    var branch = 'All';
    var company = 'All';
    var status = 'Active';
    var role = [];
    var date_counter = true;

    var ot_hours = ['#reg_ot_hours', '#rd_ot_hours', '#spcl_hol_hours', '#spcl_hol_ot_hours', '#leg_hol_hours', '#leg_hol_ot_hours'];
    var ot_type = ['#reg_ot_amount', '#rd_ot_amount', '#spcl_hol_amount', '#spcl_hol_ot_amount', '#leg_hol_amount', '#leg_hol_ot_amount'];
    
    var income_all = ['#basic_amount', '#s_accom', '#s_cola', 
                    '#s_transpo', '#mktg_comm', '#jap_comm', '#reg_ot_amount', '#rd_ot_amount',
                    '#leg_hol_amount', '#leg_hol_ot_amount', '#spcl_hol_amount',
                    '#spcl_hol_ot_amount', '#adjustments', '#thirteenth'];
    var deduction_all = ['#cash_advance', '#absence_amount', '#late_amount', '#s_sss', '#s_phic',
                        '#s_hdmf', '#others', '#under_amount', '#tax', '#man_allocation']
    var keyup = `#s_rate, #s_daily, #basic_days, #s_accom, #s_cola, #s_transpo, #mktg_comm, #jap_comm, 
                #reg_ot_hours, #rd_ot_hours, #leg_hol_hours, #spcl_hol_hours, #leg_hol_ot_hours, #spcl_hol_ot_hours,
                #thirteenth, #adjustments, #cash_advance, #absence_days, #late_hours, #s_sss, #s_phic,
                #s_hdmf, #others, #under_hours, #tax, #man_allocation, #wfh`;
    
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;
    today_converted = yyyy + '-' + mm + '-' + dd;
    var start_date = today_converted;
    var end_date = today_converted;
    $('.start_date_hidden').val(start_date);
    $('.end_date_hidden').val(end_date);

    $('#daterange').daterangepicker({
        showDropdowns: true,
        linkedCalendars: false,
        startDate: today,
        endDate: today,
    }, function(start, end){
        start_date = start.format('YYYY-MM-DD');
        $('.start_date_hidden').val(start_date);
        end_date = end.format('YYYY-MM-DD');
        $('.end_date_hidden').val(end_date);
        refresh();
    });
    
    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $("#emp_salary_modal, #salary_modal, #bulk_salary_modal").on("hidden.bs.modal", function(e){
        $('#emp_salary_form :input.required, #salary_form :input.required, #bulk_salary_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        $('#emp_salary_form .select2, #salary_form .select2, #bulk_salary_form .select2').trigger('change.select2');
        $('#b_position').val('All').trigger('change.select2');
        $('#b_status').val('Active').trigger('change.select2');
    });

    $("#filter_modal").on("hidden.bs.modal", function(e){
        var input = $('.filter_salary');
        var button = document.getElementsByClassName("filter_salary")[0];

        button.disabled = false;
        input.html('SAVE CHANGES');
    });

    function disableTabs(){
        $(`li.tab_pick`).addClass('disabled').css('cursor', 'not-allowed');
        $(`a.tab_pick`).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.tab_pick`).removeClass('disabled').css('cursor', 'pointer');
        $(`a.tab_pick`).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    };

    function refresh(){
        disableTabs();

        if(current_tab == 'Employee'){
            refresh_emp_salary_table();
        }
        else if (current_tab == 'Salary'){
            refresh_salary_table();
        }
    }

    $('.refresh_table').on('click', function(){
        refresh();
    });

    //INITIALIZE -- END

    //DATATABLE -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function refresh_emp_salary_table(){
        $('#emp_salary_table').DataTable({
            stateSave: true,
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            buttons: [
                {extend: 'excelHtml5', title: 'Employee Salary',
                exportOptions: {
                    columns: ':visible',
                },
                footer: true},
                'colvis',
            ],
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_employee_salary',
                data: {
                    branch: branch,
                    company: company,
                    status: status,
                    role: role,
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'employee.branch.name', name: 'branch'},
                {data: 'employee.company_type.name', name: 'company'},
                {data: 'employee.role.name', name: 'role'},
                {data: 'sal_type', name: 'salary_type'},
                {data: 'rate', name: 'rate'},
                {data: 'daily', name: 'daily'},
                {data: 'cola', name: 'cola'},
                {data: 'acc_allowance', name: 'acc_allowance'},
                {data: 'transpo_allowance', name: 'transpo_allowance'},
                {data: 'sss', name: 'ssss'},
                {data: 'phic', name: 'phic'},
                {data: 'hdmf', name: 'hdmf'},
                {data: 'action', orderable: false, searchable: false}
            ],
            order: [[3, 'asc']],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [5, 6, 7, 8, 9, 10, 11, 12]}
            ],
        });
    }

    function refresh_salary_table(){
        $('#salary_table').DataTable({
            stateSave: true,
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            buttons: [
                {extend: 'excelHtml5', title: 'Salary',
                exportOptions: {
                    columns: ':visible',
                },
                footer: true},
                'colvis',
            ],
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: {
                leftColumns: 1
            },
            responsive: true,
            ajax: {
                url: '/view_salary',
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    date_counter: date_counter,
                    branch: branch,
                    company: company,
                    status: status,
                    role: role
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'employee.branch.name', name: 'branch'},
                {data: 'employee.company_type.name', name: 'company'},
                {data: 'employee.role.name', name: 'role'},
                {data: 'sal_type', name: 'salary_type'},
                {data: 'rate', name: 'rate'},
                {data: 'daily', name: 'daily'},
                {data: 'net', name: 'net'},
                {data: 'gross', name: 'gross'},
                {data: 'deduction_total', name: 'deduction_total'},
                {data: 'wfh', name: 'wfh'},
                {data: 'cutoff', name: 'cutoff'},
                {data: 'pay_date', name: 'release'},
                {data: 'basic', name: 'basic'},
                {data: 'income.cola', name: 'cola'},
                {data: 'income.acc_allowance', name: 'accomm'},
                {data: 'income.transpo_allowance', name: 'transpo'},
                {data: 'income.thirteenth', name: 'thirteenth'},
                {data: 'income.adjustments', name: 'adjustment'},
                {data: 'income.market_comm', name: 'market_comm'},
                {data: 'income.jap_comm', name: 'jap_comm'},
                {data: 'reg_ot', name: 'reg_ot'},
                {data: 'rd_ot', name: 'rd_ot'},
                {data: 'spcl', name: 'spcl'},
                {data: 'leg', name: 'leg'},
                {data: 'spcl_ot', name: 'spcl_ot'},
                {data: 'leg_ot', name: 'leg_ot'},
                {data: 'deduction.cash_advance', name: 'cash_advance'},
                {data: 'absence', name: 'absence'},
                {data: 'late', name: 'late'},
                {data: 'deduction.sss', name: 'sss'},
                {data: 'deduction.phic', name: 'phic'},
                {data: 'deduction.hdmf', name: 'hdmf'},
                {data: 'deduction.others', name: 'others'},
                {data: 'undertime', name: 'undertime'},
                {data: 'deduction.man_allocation', name: 'man_allocation'},
                {data: 'deduction.tax', name: 'tax'},
                {data: 'action', orderable: false, searchable: false}
            ],
            order: [[3, 'asc']],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {
                    className: "text-right", 
                    targets: [5, 6, 7, 8, 9, 10, 13, 14, 15, 16, 17, 18, 19, 20, 21,
                            22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36]
                }
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
                    .column( 6 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer
                $( api.column( 6 ).footer() ).html(
                    '₱' + total.toFixed(2)
                );
            }
        });
    }
    
    refresh();


    //DATATABLE -- END

    //FUNCTIONS -- START

    $(document).on('click', '#filter', function(){
        $('#filter_modal').modal('show');
    })

    $(document).on('submit', '#filter_form', function(e){
        e.preventDefault();

        var input = $('.filter_salary');
        var button = document.getElementsByClassName("filter_salary")[0];

        button.disabled = true;
        input.html('FILTERING...');
        
        branch = $('#branch_filter').val();
        $('.branch_hidden').val(branch);
        company = $('#company_type_filter').val();
        $('.company_hidden').val(company);
        status = $('#status_filter').val();
        $('.status_hidden').val(status);
        role = $('#position_filter').val();
        $('.role_hidden').val('').trigger('change');

        for(let x = 0; x < role.length; x++){
            $('.role_hidden option[value="'+role[x]+'"]').prop('selected', true);
        }

        refresh();
        $('#filter_modal').modal('hide');
    })
    
    $(document).on('change', '#date_counter', function(){
        date_counter = ($(this).is(':checked')) ? true : false;
        $('.date_counter_hidden').val(date_counter);
        refresh();
    });

    $('.tab_pick').on('click', function(){
        if(!$(this).hasClass('disabled')){
            current_tab = $(this).text();
        }
    });

    //Edit Employee Salary
    $(document).on('click', '.edit_employee_salary', function(){
        let id = $(this).attr('id');
        
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_emp_salary/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#id').val(data.id);
                if(data.employee.mname){
                    $('.emp_name').text(data.employee.lname + ', ' + data.employee.fname + ' ' + data.employee.mname);
                }
                else{
                    $('.emp_name').text(data.employee.lname + ', ' + data.employee.fname);
                }

                $('.emp_branch').text(data.employee.branch.name);
                $('.emp_company').text(data.employee.company_type.name);

                $('#rate').val(data.rate);
                if(data.sal_type != 'Yen'){
                    $('#daily').val(parseFloat(data.daily).toFixed(2));
                }
                $('#type').val(data.sal_type).trigger('change');
                $('#cola').val(data.cola);
                $('#accom').val(data.acc_allowance);
                $('#transpo').val(data.transpo_allowance);
                $('#sss').val(data.sss);
                $('#phic').val(data.phic);
                $('#hdmf').val(data.hdmf);
                $('#emp_salary_modal').modal('show');
            }
        });
    });

    $('#rate').keyup(function(){
        let rate = $(this).val();

        $('#daily').val(((rate/313)*12).toFixed(2));
    });

    $(document).on('submit', '#emp_salary_form', function(e){
        e.preventDefault();

        var input = $('.save_emp_salary');
        var button = document.getElementsByClassName("save_emp_salary")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_emp_salary',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                $('#emp_salary_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        })
    });

    $(document).on('click', '.add_salary', function(){
        $('#salary_modal').modal('show');
    })

    $(document).on('change', '#emp', function(){
        if($('#s_id').val() == ''){
            let id = $(this).val();

            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/get_emp_salary/'+id,
                method: 'get',
                dataType: 'json',
                success: function(data){
                    $('#s_branch').val(data.employee.branch.name);
                    $('#s_company').val(data.employee.company_type.name);
    
                    $('#s_rate').val(data.rate);
                    if(data.sal_type != 'Yen'){
                        $('#s_daily').val(parseFloat(data.daily).toFixed(2));
                    }
                    $('#s_cola').val((data.cola == 0) ? '' : data.cola);
                    $('#s_accom').val((data.acc_allowance == 0.) ? '' : data.acc_allowance);
                    $('#s_transpo').val((data.transpo_allowance == 0) ? '' : data.transpo_allowance);
                    $('#s_sss').val((data.sss == 0) ? '' : data.sss);
                    $('#s_phic').val((data.phic == 0) ? '' : data.phic);
                    $('#s_hdmf').val((data.hdmf == 0) ? '' : data.hdmf);
                    $('#s_type').val(data.sal_type).trigger('change');
                },
                error: function(data){
                    swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                }
            });
        }
    });

    $(document).on('keyup', keyup, function(){
        if($(this).attr('id') == 's_rate' && $('#s_type') != 'Yen'){
            $('#s_daily').val(parseFloat(($('#s_rate').val()/313)*12).toFixed(2));
        }
        calculate_all();
    });

    $(document).on('change', '#s_type', function(){
        calculate_all();
    })

    function calculate_all(){
        let rate = $('#s_rate').val();
        let type = $('#s_type').val();
        let daily = $('#s_daily').val();

        //INCOME -- START
        if(type == 'Monthly'){
            $('#basic_amount').val(rate / 2).trigger('change');
        }
        else if(type == 'Daily'){
            let basic_days = $('#basic_days').val();
            $('#basic_amount').val((daily * basic_days).toFixed(2)).trigger('change');
        }

        for(let x = 0; x < ot_hours.length; x++){
            let amount = (daily / 8) * $(ot_hours[x]).val();

            if(ot_type[x] == '#reg_ot_amount'){
                amount = amount * 1.25;
            }
            if(ot_type[x] == '#rd_ot_amount'){
                amount = amount * 1.3;
            }
            else if(ot_type[x] == '#spcl_hol_amount' || ot_type[x] == '#spcl_hol_ot_amount'){
                amount = amount + (amount * 0.3);
            }
            else if(ot_type[x] == '#leg_hol_amount' || ot_type[x] == '#leg_hol_ot_amount'){
                amount = amount * 2;
            }

            $(ot_type[x]).val((amount).toFixed(2)).trigger('change');
        }

        let gross = 0;
        for(let x = 0; x < income_all.length; x++){
            if($(income_all[x]).val() != ''){
                gross += parseFloat($(income_all[x]).val());
            }
        }
        $('#gross').val(gross.toFixed(2));
        //INCOME -- END

        //DEDUCTIONS -- START
        $('#absence_amount').val((daily * $('#absence_days').val()).toFixed(2));
        $('#late_amount').val(((daily / 8) * $('#late_hours').val()).toFixed(2));
        $('#under_amount').val(((daily / 8) * $('#under_hours').val()).toFixed(2));

        let deduction = 0;
        for(let x = 0; x < deduction_all.length; x++){
            if($(deduction_all[x]).val() != ''){
                deduction += parseFloat($(deduction_all[x]).val());
            }
        }
        $('#deduction').val(deduction.toFixed(2));
        //DEDUCTIONS -- END

        //NET PAY -- START
        let net = gross - deduction;
        if($('#wfh').val() != '' || $('#wfh').val() > 0){
            net = net * ($('#wfh').val() / 100);
        }
        $('#net').val(net.toFixed(2));
        //NET PAY -- END
    }

    $(document).on('submit', '#salary_form', function(e){
        e.preventDefault();

        var input = $('.save_salary');
        var button = document.getElementsByClassName("save_salary")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_salary',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                $('#salary_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        })
    });

    $(document).on('click', '.edit_salary', function(){
        let id = $(this).attr('id');

        $.ajax({
            url: '/get_sal_mon/'+id,
            dataType: 'json',
            success: function(data){
                $('#s_id').val(data.id);
                $('#emp').val(data.employee.id).trigger('change');
                $('#s_branch').val(data.employee.branch.name);
                $('#s_company').val(data.employee.company_type.name);
                $('#s_rate').val(data.rate);
                if(data.sal_type != 'Yen'){
                    $('#s_daily').val(parseFloat(data.daily).toFixed(2));
                }
                $('#cutoff_from').val(data.period_from);
                $('#cutoff_to').val(data.period_to);
                $('#release').val(data.pay_date);

                $('#basic_days').val(data.income.basic);
                $('#s_accom').val(data.income.acc_allowance);
                $('#s_cola').val(data.income.cola);
                $('#s_transpo').val(data.income.transpo_allowance);
                $('#mktg_comm').val(data.income.market_comm);
                $('#jap_comm').val(data.income.jap_comm);
                $('#reg_ot_hours').val(data.income.reg_ot);
                $('#rd_ot_hours').val(data.income.rd_ot);
                $('#thirteenth').val(data.income.thirteenth);
                $('#leg_hol_hours').val(data.income.leg_hol);
                $('#spcl_hol_hours').val(data.income.spcl_hol);
                $('#leg_hol_ot_hours').val(data.income.leg_hol_ot);
                $('#spcl_hol_ot_hours').val(data.income.spcl_hol_ot);
                $('#adjustments').val(data.income.adjustments);

                $('#cash_advance').val(data.deduction.cash_advance);
                $('#absence_days').val(data.deduction.absence);
                $('#late_hours').val(data.deduction.late);
                $('#s_sss').val(data.deduction.sss);
                $('#s_phic').val(data.deduction.phic);
                $('#s_hdmf').val(data.deduction.hdmf);
                $('#others').val(data.deduction.others);
                $('#under_hours').val(data.deduction.undertime);
                $('#tax').val(data.deduction.tax);
                $('#man_allocation').val(data.deduction.man_allocation);
                $('#wfh').val(data.deduction.wfh);

                $('#s_type').val(data.sal_type).trigger('change'); // will trigger to calculate all

                $('#salary_modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete_salary', function(){
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
                                title: 'Are you sure?',
                                text: 'You are about to delete a salary. This may affect multiple rows',
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
                                        url: '/delete_salary',
                                        method: 'get',
                                        data: {
                                            id:id,
                                            password:password
                                        },
                                        type: 'json',
                                        success:function(data){
                                            notif('Deleted!', 'This Salary has been Deleted', 'success', 'glyphicon-ok');
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

    $(document).on('click', '.bulk_add_salary', function(){
        $('#bulk_salary_modal').modal('show');
    });

    $(document).on('submit', '#bulk_salary_form', function(e){
        e.preventDefault();

        var input = $('.bulk_save_salary');
        var button = document.getElementsByClassName("bulk_save_salary")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/bulk_save_salary',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                $('#bulk_salary_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh();
            },
            error: function(data){
                swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        })
    });

    //SELECT 2
    $('#emp').select2({
        placeholder: 'Select Employee',
        ajax: {
            url: "/emp_salary_select",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page,
                    date: $('#release').val(),
                    status: $('#status').val(),
                    role: $('#position').val()
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.text;
        },
    });

    $('#b_emp').select2({
        closeOnSelect: false,
        placeholder: 'Select Employee',
        ajax: {
            url: "/emp_salary_select",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page: params.page,
                    date: $('#b_release').val(),
                    status: $('#b_status').val(),
                    role: $('#b_position').val()
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results      
                }
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.text;
        },
    }).on('select2:selecting', e => $(e.currentTarget).data('scrolltop', $('.select2-results__options').scrollTop()))
    .on('select2:select', e => $('.select2-results__options').scrollTop($(e.currentTarget).data('scrolltop')));

    $('#position').select2({
        placeholder: 'Select Position',
        ajax: {
            url: "/salary_position_select",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page,
                    date: $('#release').val(),
                    status: $('#status').val(),
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results
                }
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.text;
        },
    });

    $('#b_position').select2({
        placeholder: 'Select Position',
        ajax: {
            url: "/salary_position_select",
            dataType: 'json',

            data: function (params){
                return {
                    name: params.term,
                    page:params.page,
                    date: $('#b_release').val(),
                    status: $('#b_status').val(),
                }
            },
            
            processResults: function (data){
                return {
                    results:data.results
                }
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        },
        templateResult: function(data) {
            return data.text;
        },
    });

    $('#position_filter').select2({
        closeOnSelect: false
    }).on('select2:selecting', e => $(e.currentTarget).data('scrolltop', $('.select2-results__options').scrollTop()))
    .on('select2:select', e => $('.select2-results__options').scrollTop($(e.currentTarget).data('scrolltop')));

    //FUNCTIONS -- END
})