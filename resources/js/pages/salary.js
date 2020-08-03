const { indexOf } = require("lodash");

$(document).ready(function(){
    
    //INITIALIZE -- START

    var current_tab = 'Employee';
    var branch = 'All';
    var company = 'All';
    var ot_hours = ['#reg_ot_hours', '#spcl_hol_hours', '#spcl_hol_ot_hours', '#leg_hol_hours', '#leg_hol_ot_hours'];
    var ot_type = ['#reg_ot_amount', '#spcl_hol_amount', '#spcl_hol_ot_amount', '#leg_hol_amount', '#leg_hol_ot_amount'];
    
    var income_all = ['#basic_amount', '#s_accom', '#s_cola', 
                    '#s_transpo', '#mktg_comm', '#jap_comm', '#reg_ot_amount', 
                    '#leg_hol_amount', '#leg_hol_ot_amount', '#spcl_hol_amount',
                    '#spcl_hol_ot_amount', '#adjustments', '#thirteenth'];
    var deduction_all = ['#cash_advance', '#absence_amount', '#late_amount', '#s_sss', '#s_phic',
                        '#s_hdmf', '#others', '#under_amount', '#tax', '#man_allocation']
    var keyup = `#s_rate, #s_daily, #basic_days, #s_accom, #s_cola, #s_transpo, #mktg_comm, #jap_comm, 
                #reg_ot_hours, #leg_hol_hours, #spcl_hol_hours, #leg_hol_ot_hours, #spcl_hol_ot_hours,
                #thirteenth, #adjustments, #cash_advance, #absence_days, #late_hours, #s_sss, #s_phic,
                #s_hdmf, #others, #under_hours, #tax, #man_allocation, #wfh`;
    
    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });
    
    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $("#emp_salary_modal, #salary_modal").on("hidden.bs.modal", function(e){
        $('#emp_salary_form, :input.required, #salary_form, :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        $('#emp_salary_form .select2, #salary_form .select2').trigger('change.select2');
        console.log('mao ni');
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

    //INITIALIZE -- END

    //DATATABLE -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function refresh_emp_salary_table(){
        $('#emp_salary_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            buttons: [
                {extend: 'excelHtml5', title: 'Expense',
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
                /*data: {
                    branch: branch,
                    company: company,
                }*/
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'employee.branch.name', name: 'branch'},
                {data: 'employee.company_type.name', name: 'company'},
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
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [4, 5, 6, 7, 8, 9, 10, 11]}
            ],
        });
    }

    function refresh_salary_table(){
        $('#salary_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();  
            },
            dom: 'Bflrtip',
            buttons: [
                {extend: 'excelHtml5', title: 'Expense',
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
                /*data: {
                    branch: branch,
                    company: company,
                }*/
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'employee.branch.name', name: 'branch'},
                {data: 'employee.company_type.name', name: 'company'},
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
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {
                    className: "text-right", 
                    targets: [4, 5, 6, 7, 8, 9, 12, 13, 14, 15, 16, 17, 18, 19, 20,
                            21, 22 ,23 ,24 ,25, 26 ,27 ,28, 29, 30, 31, 32, 33, 34]
                }
            ],
        });
    }
    
    refresh();


    //DATATABLE -- END

    //FUNCTIONS -- START

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
                $('#daily').val(data.daily);
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
        let id = $(this).val();

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_emp_salary/'+id,
            method: 'get',
            dataType: 'json',
            success: function(data){
                $('#s_id').val(data.id);
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
                    date: $('#release').val()
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

    //FUNCTIONS -- END
})