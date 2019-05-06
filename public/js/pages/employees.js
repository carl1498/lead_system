$(document).ready(function(){

    //INITIALIZE -- START

    var current_branch = 'Makati';

    $(".datepicker").datepicker({
        format: 'yyyy-mm-dd',
        forceParse: false
    });
    
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('.select2').select2();

    function refresh_tables(){//reload datatable ajax
        employees_branch.ajax.reload();
    }

    $('#employee_modal').on('shown.bs.modal', function(){
        $('#fname').focus();
    });

    $("#employee_modal").on("hidden.bs.modal", function(e){
        $('#employee_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        });
        $(this).find("input,textarea,select").val('').end();
        $('.select2').trigger('change');
    });

    $("#account_modal").on("hidden.bs.modal", function(e){
        $(this).find("input,textarea,select").val('').end();
    });

    $('input, select').attr('autocomplete', 'off');

    $('.box-profile').slimScroll({
        size: '8px',
        height: 'auto',
        alwaysVisible: true
    });
    
    //INITIALIZE -- END

    //DATATABLES -- START

    $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
    });

    var columns_employees = [
        {data: 'name', name: 'name'},
        {data: 'contact_personal', name: 'contact_personal'},
        {data: 'contact_business', name: 'contact_business'},
        {data: 'birthdate', name: 'birthdate'},
        {data: 'gender', name: 'gender'},
        {data: 'email', name: 'email'},
        {data: 'role.name', name: 'role.name'},
        {data: 'hired_date', name: 'hired_date'},
        {data: "action", orderable:false,searchable:false}
    ]

    function refresh_employee_branch(){
        var employees_branch = $('#employees_branch').DataTable({
            destroy: true,
            scrollX: true,
            scrollCollapse: true,
            fixedColumns: true,
            ajax: {
                url: '/employee_branch/{current_branch}',
                data: {current_branch: current_branch}
            },
            columns: columns_employees,
        });
    }

    refresh_employee_branch();

    //DATATABLES -- END

    //FUNCTIONS -- START

    $('.tab_pick').on('click', function(){
        current_branch = $(this).text();
        refresh_employee_branch();
    });

    $('.add_employee').on('click', function(){
        $('#add_edit').val('add');
        $('#employee_modal').modal('toggle');
        $('#employee_modal').modal('show');
    });

    //Add or Edit School
    $('.save_employee').on('click', function(e){
        e.preventDefault();

        var input = $(this);
        var button = this;

        button.disabled = true;
        input.html('SAVING...');

        var formData = new FormData($('#employee_form')[0]);
        
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/save_employee',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data){
                swal('Success!', 'Record has been saved to the Database!', 'success');
                $('#employee_modal').modal('hide');
                button.disabled = false;
                input.html('SAVE CHANGES');
                refresh_employee_branch();
            },
            error: function(data){
                swal("Oh no!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        })
    })

    //Open Employee Modal (ADD)
    $('.add_employee').on('click', function(){
    });

    //Open Employee Modal (EDIT)
    $(document).on('click', '.edit_employee', function(){
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_employee/{id}',
            method: 'get',
            data: {id: id},
            dataType: 'json',
            success:function(data){
                $('#add_edit').val('edit');
                $('#id').val(data.employee.id);
                $('#fname').val(data.employee.fname);
                $('#mname').val(data.employee.mname);
                $('#lname').val(data.employee.lname);
                $('#birthdate').val(data.employee.birthdate);
                $('#gender').val(data.employee.gender).trigger('change');
                $('#personal_no').val(data.employee.contact_personal);
                $('#business_no').val(data.employee.contact_business);
                $('#email').val(data.employee.email);
                //reserved for picture
                $('#address').val(data.employee.address);
                $('#branch').val(data.employee.branch_id).trigger('change');
                $('#role').val(data.employee.role_id).trigger('change');
                $('#salary').val(data.employee.salary);
                $('#hired').val(data.employee.hired_date);
                $('#sss').val(data.benefits[0].id_number);
                $('#pagibig').val(data.benefits[1].id_number);
                $('#philhealth').val(data.benefits[2].id_number);
                $('#tin').val(data.benefits[3].id_number);
                $('#employee_modal').modal('toggle');
                $('#employee_modal').modal('show');
            }
        });
    });

    //Delete Employee
    $(document).on('click', '.delete_employee', function(){
        var id = $(this).attr('id');

        swal({
            title: 'Are you sure?',
            text: 'You are about to delete an employee. This may affect multiple rows',
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
                    url: '/delete_employee',
                    method: 'get',
                    data: {id:id},
                    type: 'json',
                    success:function(data){
                        swal('Deleted!', 'This Employee has been Deleted', 'success');
                        refresh_employee_branch();
                    }
                })
            }
        });
    });

    //ACCOUNTS -- START

    $(document).on('click', '.edit_account', function(){
        var id = $(this).attr('id');

        $.ajax({
            url: '/get_account/'+id,
            dataType: 'json',
            success: function(data){
                $('#a_id').val(data.id);
                if(data.employee.mname){
                    $('#emp_name').val(data.employee.lname + ', ' + data.employee.fname + ' ' + data.employee.mname);
                }
                else{
                    $('#emp_name').val(data.employee.lname + ', ' + data.employee.fname);
                }
                $('#a_email').val(data.email);
                
                $('#account_modal').modal('toggle');
                $('#account_modal').modal('show');
            }
        })
    })

    $('.save_account').on('click', function(e){
        e.preventDefault();

        if($('#password').val() != ''){
            if($('#password').val() != $('#confirm_password').val()){
                swal('Error!', 'Password and Confirm Password must be identical', 'error');
                return;
            }
        }

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
                                url: '/save_account',
                                data: $('#account_form').serialize(),
                                method: 'POST',
                                dataType: 'text',
                                success: function(data){
                                    swal('Success!', 'User Data has been saved!', 'success');
                                    $('#account_modal').modal('hide');
                                    refresh_employee_branch();
                                }
                            })
                        }
                    }
                });
            },
        });
    });

    //ACCOUNTS -- END

    //RESIGN -- START

    $(document).on('click', '.resign_employee', function(){
        var id = $(this).attr('id');


        $('#r_id').val(id);
        $('#resign_modal').modal('toggle');
        $('#resign_modal').modal('show');
    });

    $('.save_resign_employee').on('click', function(e){
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
                                url: '/save_resign_employee',
                                method: 'POST',
                                dataType: 'text',
                                data: $('#resign_form').serialize(),
                                success: function (data){
                                    $('#resign_modal').modal('hide');
                                    swal('Employee now resigned.', '', 'info');
                                    refresh_employee_branch();
                                }
                            });
                        }
                    }
                });
            },
        });
    });

    //RESIGN -- END

    //REHIRE -- START

    $(document).on('click', '.rehire_employee', function(){
        var id = $(this).attr('id');


        $('#rh_id').val(id);
        $('#rehire_modal').modal('toggle');
        $('#rehire_modal').modal('show');
    });

    $('.save_rehire_employee').on('click', function(e){
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
                                url: '/save_rehire_employee',
                                method: 'POST',
                                dataType: 'text',
                                data: $('#rehire_form').serialize(),
                                success: function (data){
                                    $('#rehire_modal').modal('hide');
                                    swal('Success!', 'Employee now rehired', 'success');
                                    refresh_employee_branch();
                                }
                            });
                        }
                    }
                });
            },
        });
    });

    //REHIRE -- END

    //VIEW PROFILE -- START

    $(document).on('click', '.view_employee_profile', function(){
        var id = $(this).attr('id');

        $.ajax({
            url: '/view_profile_employee/'+id,
            method: 'get',
            dataType: 'json',
            success: function(data){
                if(data.mname){
                    $('#p_emp_name').text(data.lname + ', ' + data.fname + ' ' + data.mname);
                }
                else{
                    $('#p_emp_name').text(data.lname + ', ' + data.fname);
                }

                $('#p_position').text(data.role.name);

                $('#p_business').text(data.contact_business ? data.contact_business : '-');
                $('#p_personal').text(data.contact_personal ? data.contact_personal : '-');
                var age = getAge(data.birthdate);
                $('#p_birthdate').text(data.birthdate + ' (' + age + ')');
                $('#p_gender').text(data.gender);
                $('#p_branch').text(data.branch.name);
                $('#p_status').text(data.employment_status);
                $('#p_hired').text(data.hired_date ? data.hired_date : '-');
                $('#p_resigned').text(data.resignation_date ? data.resignation_date : '-');
                $('#p_sss').text(data.benefits[0].id_number ? data.benefits[0].id_number : '-');
                $('#p_pagibig').text(data.benefits[1].id_number ? data.benefits[1].id_number : '-');
                $('#p_philhealth').text(data.benefits[2].id_number ? data.benefits[2].id_number : '-');
                $('#p_tin').text(data.benefits[3].id_number ? data.benefits[3].id_number : '-');
            }
        });
    }); 

    //VIEW PROFILE -- END

    function getAge(birthdate){
        var today = new Date();
        var dd = today.getDate();
        var mm = today.getMonth() + 1;
        var yyyy = today.getFullYear();
        
        today = yyyy + '-' + mm + '-' + dd;
        birth_array = birthdate.split('-');

        var age = yyyy - parseInt(birth_array[0]);

        if(mm == parseInt(birth_array[1])){
            if(dd < birth_array[2]){
                age--;
            }
        }
        else if(mm < parseInt(birth_array[1])){
            age--;
        }

        return age;
    }

    //FUNCTIONS -- END
});