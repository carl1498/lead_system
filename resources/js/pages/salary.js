$(document).ready(function(){
    
    //INITIALIZE -- START

    var current_tab = 'Company';
    var branch = 'All';
    var company = 'All';
    
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

    function enableTabs(){
        console.log('enable tabs');
    };

    //INITIALIZE -- END

    //DATATABLE -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();

        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    function refresh_expense_table(){
        $('#employee_table').DataTable({
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
    refresh_expense_table();


    //DATATABLE -- END

    //FUNCTIONS -- START

    $(document).on('click', '.edit_employee_salary', function(){
        let id = $(this).attr('id')
    });

    //FUNCTIONS -- END
})