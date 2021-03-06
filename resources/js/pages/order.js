$(document).ready(function(){

    //INITIALIZE -- START

    var current_tab = 'Student';
    
    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });
    
    $('.timepicker').timepicker({
        showSeconds: false,
        showMeridian: false,
        defaultTime: false,
        showInputs: false,
        pickTime: false,
        template: false,
    });

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $("#order_modal").on("hidden.bs.modal", function(e){
        order_modal_clear();
        $('#order_continuous').bootstrapToggle('off');
    });

    function order_modal_clear(){
        $('#order_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#order_form').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#order_continuous').bootstrapToggle('off');

    function disableTabs(){
        $(`li.order_pick`).addClass('disabled').css('cursor', 'not-allowed');

        $(`a.order_pick`).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.order_pick`).removeClass('disabled').css('cursor', 'pointer');

        $(`a.order_pick`).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }
    
    function refresh(){
        disableTabs();
        refresh_order_table();
        setTimeout(function(){
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 1000);
    }

    //INITIALIZE -- END
    
    //DATATABLES -- START
    
    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();
    });

    function refresh_order_table(){
        $('#order_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs(); 
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_order',
                data: {
                    current_tab: current_tab
                }
            },
            columns: [
                {data: 'client.name', name: 'client'},
                {data: 'order_type.name', name: 'order_type'},
                {data: 'no_of_orders', name: 'orders'},
                {data: 'no_of_hires', name: 'hires'},
                {data: 'interview_date', name: 'interview date'},
                {data: 'time', name: 'time'},
                {data: 'remarks', name: 'remarks'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {defaultContent: "", targets: "_all"},
                {className: "text-right", targets: [2, 3]}
            ],
            order: [[4, 'desc']],
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
                    .column( 2 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );

                // Total over all pages
                total2 = api
                    .column( 3 )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
                    

                // Total over this page
                pageTotal2 = api
                    .column( 3, { page: 'current'} )
                    .data()
                    .reduce( function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0 );
     
                // Update footer hires
                $( api.column( 3 ).footer() ).html(
                    total2
                );

                // Update footer orders
                $( api.column( 2 ).footer() ).html(
                    (total-total2) + '/' + total
                );


            }
        });
    }

    //DATATABLES -- END
    
    //FUNCTIONS -- START

    $(document).on('click', '.order_pick', function(){
        current_tab = $(this).text();
    });

    $(document).on('click', '.edit_order', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_order/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#id').val(data.id);
                $('#add_edit').val('edit');
                $('#client').val(data.client_id).trigger('change');
                $('#order_type').val(data.order_type_id).trigger('change');
                $('#industry').val(data.industry).trigger('change');
                $('#order').val(data.no_of_orders);
                $('#hires').val(data.no_of_hires);
                $('#interview_date').val(data.interview_date);
                $('#start_time').val(data.start_time);
                $('#end_time').val(data.end_time);
                $('#remarks').val(data.remarks);
                $('#order_modal').modal('show');
            }
        });
    });

    $(document).on('click', '.delete_order', function(){
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
                                text: 'You are about to delete an order.',
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
                                        url: '/delete_order',
                                        method: 'get',
                                        data: {
                                            id:id,
                                            password:password
                                        },
                                        type: 'json',
                                        success:function(data){
                                            notif('Deleted!', 'This Order has been Deleted', 'success', 'glyphicon-ok');
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
    })

    $(document).on('click', '.add_order', function(){
        $('#add_edit').val('add');
        $('#order_modal').modal('toggle');
        $('#order_modal').modal('show');
    });

    $(document).on('submit', '#order_form', function(e){
        e.preventDefault();

        var input = $('.save_order');
        var button = document.getElementsByClassName("save_order")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_order',
            method: 'POST',
            data: $(this).serialize(),
            success:function(data){
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                if($('#order_continuous').is(':checked')){
                    order_modal_clear();
                    $('#add_edit').val('add');
                }
                else{
                    $('#order_modal').modal('hide')
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

    $(document).on('click', '.refresh_table', function(){
        refresh();
    });

    refresh();
    $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();

    $('#client').select2({
        allowClear: true,
        placeholder: 'Select Client',
        ajax: {
            url: "/clientAll",
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