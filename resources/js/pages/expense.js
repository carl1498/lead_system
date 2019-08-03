$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Expense'; //Expense, Type, Particular
    var date_counter = true;

    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    var today = new Date();
    var dd = String(today.getDate()).padStart(2, '0');
    var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
    var yyyy = today.getFullYear();

    today = mm + '/' + dd + '/' + yyyy;
    var start_date = today;
    var end_date = today;

    $('#daterange').daterangepicker({
        showDropdowns: true,
        linkedCalendars: false,
        startDate: today,
        endDate: today,
    }, function(start, end){
        start_date = start.format('YYYY-MM-DD');
        end_date = end.format('YYYY-MM-DD');
        refresh();
    });

    $(document).on('change', '#date_counter', function(){
        date_counter = ($(this).is(':checked')) ? true : false;
        refresh();
    });

    $("#expense_type_modal").on("hidden.bs.modal", function(e){
        $('#expense_type_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });

    $("#expense_particular_modal").on("hidden.bs.modal", function(e){
        $('#expense_particular_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });

    $("#expense_modal").on("hidden.bs.modal", function(e){
        $('#expense_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    });

    function refresh(){
        if(current_tab == 'Expense'){
            refresh_expense_table();
        }
        else if(current_tab == 'Type'){
            refresh_type_table();
        }
        else if(current_tab == 'Particular'){//Particular
            refresh_particular_table()
        }
        else{
            refresh_cash_disbursement_table();
        }
    }

    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function refresh_expense_table(){
        $('#expense_table').DataTable({
            initComplete: function(settings, json) {
                //enableTabs();  
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_expense',
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    date_counter
                }
            },
            columns: [
                {data: 'type.name', name: 'type'},
                {data: 'particular', name: 'particular'},
                {data: 'branch.name', name: 'branch'},
                {data: 'company_type.name', name: 'company'},
                {data: 'date', name: 'date'},
                {data: 'amount', name: 'amount'},
                {data: 'vat', name: 'vat'},
                {data: 'input_tax', name: 'input_tax'},
                {data: 'check_voucher', name: 'check_voucher'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}],
            order: [4, 'desc']
        });
    }

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
        var particular_table = $('#particular_table').DataTable({
            initComplete: function(settings, json) {
                //enableTabs();  
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_expense_particular',
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'tin', name: 'tin'},
                {data: 'address', name: 'address'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}],
        });
    }

    refresh();
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    
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

    $(document).on('click', '.edit_expense_type', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_expense_type/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#t_id').val(data.id);
                $('#t_add_edit').val('edit');
                $('#expense_type_name').val(data.name);
                $('#expense_type_modal').modal('show');
            }
        })
    });

    $(document).on('click', '.edit_expense_particular', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_expense_particular/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#p_id').val(data.id);
                $('#p_add_edit').val('edit');
                $('#expense_particular_name').val(data.name);
                $('#expense_particular_tin').val(data.tin);
                $('#expense_particular_address').val(data.address);
                $('#expense_particular_modal').modal('show');
            }
        })
    });

    $(document).on('click', '.edit_expense', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_expense/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#e_id').val(data.id);
                $('#e_add_edit').val('edit');
                $('#expense_type').val(data.expense_type_id).trigger('change');
                $('#expense_particular').val(data.expense_particular_id).trigger('change');
                $('#branch').val(data.branch_id).trigger('change');
                $('#company_type').val(data.lead_company_type_id).trigger('change');
                $('#date').val(data.date);
                $('#amount').val(data.amount);
                $('#vat').val(data.vat).trigger('change');
                $('#input_tax').val(data.input_tax);
                $('#check_voucher').val(data.check_voucher);
                $('#remarks').val(data.remarks);
                $('#expense_modal').modal('show');
            }
        })
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

    $(document).on('submit', '#expense_particular_form', function(e){
        e.preventDefault();

        var input = $('.save_expense_particular');
        var button = document.getElementsByClassName("save_expense_particular")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_expense_particular',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#expense_particular_modal').modal('hide');
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

    $(document).on('submit', '#expense_form', function(e){
        e.preventDefault();

        var input = $('.save_expense');
        var button = document.getElementsByClassName("save_expense")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_expense',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                $('#expense_modal').modal('hide');
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

    $('#amount').keyup(function(){
        if($('#vat').val() == 'VAT'){
            $('#input_tax').val(parseFloat($(this).val()/1.12*0.12).toFixed(2));
        }
        else{
            $('#input_tax').val(0);
        }
    });

    $(document).on('change', '#vat', function(){
        if($(this).val() == 'VAT'){
            if($('#amount').val() != ''){
                $('#input_tax').val(parseFloat($('#amount').val()/1.12*0.12).toFixed(2));
            }
        }
        else{
            $('#input_tax').val(0);
        }
    });

    $('#expense_type').select2({
        allowClear: true,
        placeholder: 'Select Type',
        ajax: {
            url: "/expenseTypeAll",
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

    $('#expense_particular').select2({
        allowClear: true,
        placeholder: 'Select Particular',
        ajax: {
            url: "/expenseParticularAll",
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

    function refresh_cash_disbursement_table(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/view_cash_disbursement',
            data: {
                start_date: start_date,
                end_date: end_date,
                date_counter
            },
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#cash_disbursement_table').empty();

                let html = '';
                let type = '';
                let type_total = '';

                html += '<thead>';

                for(let x = 0; x < data.expense_type.length; x++){
                    type += '<th class="expense_types">' + data.expense_type[x].name + '</th>';
                    type_total += '<th class="expense_types">' + data.expense_type[x].expense_type_total + '</th>';
                }

                html += `
                <tr>
                    <th rowspan="2" style="width: 100px;">Date</th>
                    <th rowspan="2" style="width: 130px;">Check Voucher</th>
                    <th rowspan="2" style="width: 350px;">Particulars</th>
                    <th rowspan="2" style="width: 130px;">TIN Number</th>
                    <th rowspan="2" style="width: 350px;">Address</th>
                    <th style="width: 150px;">Total Invoice</th>
                    <th style="width: 150px;">Non Vat</th>
                    <th style="width: 150px;">Vatable Amount</th>
                    <th style="width: 150px;">Input Tax</th>`
                    +type+
                `</tr>
                <tr>
                    <th>`+data.total+`</th>
                    <th>`+data.non_vat+`</th>
                    <th>`+data.vat+`</th>
                    <th>`+data.input_tax+`</th>`
                    +type_total+
                `</tr>`

                html += '</thead>';

                html += '<tbody>';
                for(let x = 0; x < data.expense.length; x++){
                    let particular = '';
                    for(let y = 0; y < data.expense_type.length; y++){
                        particular += '<td>' + data.expense_particular_type_total[x][y] + '</td>';
                    }
                    html += '<tr>'+
                        '<td>' + data.expense[x].date + '</td>'+
                        '<td>' + ((data.expense[x].check_voucher) ? data.expense[x].check_voucher : '') + '</td>'+
                        '<td>' + data.expense[x].particular.name + '</td>'+
                        '<td>' + ((data.expense[x].particular.tin) ? data.expense[x].particular.tin : '') + '</td>'+
                        '<td>' + ((data.expense[x].particular.address) ? data.expense[x].particular.address : '') + '</td>'+
                        '<td>' + data.expense[x].total_invoice + '</td>'+
                        '<td>' + data.expense[x].non_vat_total + '</td>'+
                        '<td>' + data.expense[x].vat_total + '</td>'+
                        '<td>' + data.expense[x].input_tax_total + '</td>'
                        +particular+
                    '</tr>';
                }

                html += '</tbody>';

                $('#cash_disbursement_table').append(html);

                $("#cash_disbursement_table").tableExport({
                    position: 'top',
                    formats: ['xlsx'],
                    bootstrap: true
                });
            }
        });
    }

    //FUNCTIONS -- END

})