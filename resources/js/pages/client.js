$(document).ready(function(){
  
    //INITIALIZE -- START

    var current_tab = 'Japanese School'; //Japanese School, Medical Facility, Manpower Agency
    var client_id;
    
    $('.select2').select2();

    $('input, select').attr('autocomplete', 'off');

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $("#client_modal").on("hidden.bs.modal", function(e){
        client_modal_clear();
        $('#client_continuous').bootstrapToggle('off');
    });

    function client_modal_clear(){
        $('#client_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $('#client_form').find("input,textarea,select").val('').end();
        $('.select2').trigger('change.select2');
    }

    $('#client_continuous').bootstrapToggle('off');
    
    $('#client_view_pic_modal').on('shown.bs.modal', function(){
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    $("#client_pic_modal").on("hidden.bs.modal", function(e){
        $('#client_pic_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        setTimeout(function(){$('#client_view_pic_modal').modal('show')}, 500);
    });

    $("#client_bank_modal").on("hidden.bs.modal", function(e){
        $('#client_bank_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
    });
    
    function disableTabs(){
        $(`li.client_pick`).addClass('disabled').css('cursor', 'not-allowed');

        $(`a.client_pick`).addClass('disabled').css('pointer-events', 'none');

        $('.refresh_table').attr('disabled', true);
    }

    function enableTabs(){
        $(`li.client_pick`).removeClass('disabled').css('cursor', 'pointer');

        $(`a.client_pick`).removeClass('disabled').css('pointer-events', 'auto');

        $('.refresh_table').attr('disabled', false);
    }

    function refresh(){
        disableTabs();
        refresh_client_table();
        setTimeout(function(){
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }, 1000);
    }
    
    $('.refresh_table').on('click', function(){
        refresh();
    });

    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        refresh();
    });

    function refresh_client_table(){
        $('#client_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs(); 
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_client',
                data: {
                    current_tab: current_tab
                }
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'address', name: 'address'},
                {data: 'contact', name: 'contact'},
                {data: 'email', name: 'email'},
                {data: 'industry.name', name: 'industry'},
                {data: 'url', name: 'url'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}]
        });
    }

    function refresh_pic_table(id){
        $('#client_pic_table').DataTable({
            initComplete: function(settings, json) {
                enableTabs();  
            },
            processing: true,
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            responsive: true,
            ajax: {
                url: '/view_client_pic/'+id,
            },
            columns: [
                {data: 'name', name: 'name'},
                {data: 'position', name: 'position'},
                {data: 'contact', name: 'contact'},
                {data: 'email', name: 'email'},
                {data: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [{defaultContent: "", targets: "_all"}]
        });
    }

    refresh();

    //DATATABLES -- END

    //FUNCTIONS -- START

    $(document).on('click', '.client_pick', function(){
        current_tab = $(this).text();
    });

    $(document).on('click', '.pic', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_client_pic/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                client_id = id;
                $('.title_company').text(data.name);
                refresh_pic_table(data.id);
                $('#client_view_pic_modal').modal('show');
            }
        });
    });

    $(document).on('click', '.bank', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_bank/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#b_client_id').val(id);
                $('.title_bank').text(data.client.name);
                if(data.client_bank != 'false'){
                    $('#b_id').val(data.client_bank.id);
                    $('#b_add_edit').val('edit');
                    $('#bank_name').val(data.client_bank.bank_name);
                    $('#swift_code').val(data.client_bank.swift_code);
                    $('#bank_branch_name').val(data.client_bank.branch_name);
                    $('#bank_address').val(data.client_bank.address);
                    $('#account_name').val(data.client_bank.account_name);
                    $('#account_number').val(data.client_bank.account_number);
                    $('#bank_contact').val(data.client_bank.contact);
                }
                else{
                    $('#b_add_edit').val('add');
                }
                $('#client_bank_modal').modal('show');
            }
        });
    })

    $(document).on('click', '.add_pic', function(){
        $(this).attr('id');

        $('#p_client_id').val(client_id);
        $('#p_add_edit').val('add');
        $('#client_view_pic_modal').modal('hide');
        setTimeout(function(){$('#client_pic_modal').modal('show')}, 500);
    });

    $(document).on('click', '.add_client', function(){
        $('#add_edit').val('add');
        $('#client_modal').modal('toggle');
        $('#client_modal').modal('show');
    });

    $(document).on('click', '.edit_client', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_client_pic/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#id').val(data.id);
                $('#add_edit').val('edit');
                $('#company_type').val(data.client_company_type_id).trigger('change');
                $('#client_name').val(data.name);
                $('#client_address').val(data.address);
                $('#client_contact').val(data.contact);
                $('#client_email').val(data.email);
                $('#industry').val(data.ind_id).trigger('change');
                $('#client_url').val(data.url);
                $('#client_modal').modal('show');
            }
        });
    });
    
    $(document).on('click', '.edit_pic', function(){
        let id = $(this).attr('id');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_pic/'+id,
            method: 'get',
            dataType: 'json',
            success:function(data){
                $('#p_client_id').val(client_id);
                $('#p_id').val(data.id);
                $('#p_add_edit').val('edit');
                $('#p_name').val(data.name);
                $('#p_position').val(data.position);
                $('#p_contact').val(data.contact);
                $('#p_email').val(data.email);
                $('#client_view_pic_modal').modal('hide');
                setTimeout(function(){$('#client_pic_modal').modal('show')}, 500);
            }
        });
    });

    $(document).on('click', '.delete_client', function(){
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
                                text: 'You are about to delete a client. This may affect multiple rows',
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
                                        url: '/delete_client',
                                        method: 'get',
                                        data: {
                                            id:id,
                                            password:password
                                        },
                                        type: 'json',
                                        success:function(data){
                                            notif('Deleted!', 'This Client has been Deleted', 'success', 'glyphicon-ok');
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

    $(document).on('click', '.delete_pic', function(){
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
                                text: 'You are about to delete a client person in charge. This may affect multiple rows',
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
                                        url: '/delete_pic',
                                        method: 'get',
                                        data: {
                                            id:id,
                                            password:password
                                        },
                                        type: 'json',
                                        success:function(data){
                                            notif('Deleted!', 'This Client Person in Charge has been Deleted', 'success', 'glyphicon-ok');
                                            refresh_pic_table(data);
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

    $(document).on('submit', '#client_form', function(e){
        e.preventDefault();

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
                            $.ajax({
                                headers: {
                                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/save_client',
                                method: 'POST',
                                data: $('#client_form').serialize() + '&password=' + password,
                                success:function(data){
                                    notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                                    if($('#client_continuous').is(':checked')){
                                        client_modal_clear();
                                        $('#add_edit').val('add');
                                    }
                                    else{
                                        $('#client_modal').modal('hide')
                                    }
                                    refresh();
                                },
                                error: function(data){
                                    swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    $(document).on('submit', '#client_pic_form', function(e){
        e.preventDefault();

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
                            $.ajax({
                                headers: {
                                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/save_client_pic',
                                method: 'POST',
                                data: $('#client_pic_form').serialize() + '&password=' + password,
                                success:function(data){
                                    notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                                    $('#client_pic_modal').modal('hide');
                                    setTimeout(function(){$('#client_view_pic_modal').modal('show')}, 500);
                                    refresh_pic_table(data);
                                    refresh();
                                },
                                error: function(data){
                                    swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                                }
                            });
                        }
                    }
                });
            }
        });

        
    });

    $(document).on('submit', '#client_bank_form', function(e){
        e.preventDefault();

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
                            $.ajax({
                                headers: {
                                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                                },
                                url: '/save_client_bank',
                                method: 'POST',
                                data: $('#client_bank_form').serialize() + '&password=' + password,
                                success:function(data){
                                    notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                                    $('#client_bank_modal').modal('hide');
                                    refresh();
                                },
                                error: function(data){
                                    swal("Error!", "Something went wrong, please contact IT Officer.", "error");
                                }
                            });
                        }
                    }
                });
            }
        });
    });

    //FUNCTIONS -- END

})