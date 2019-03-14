@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Employee Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_employee" data-toggle="modal" data-target="#employee_modal">
                <i class="fa fa-plus-square"></i>
            </button>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <div class="col-md-3">

            <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="./img/avatar5.png" alt="User profile picture">

                    <h3 class="profile-username text-center">{{ onLoadName() }}</h3>

                    <p class="text-muted text-center">{{ onLoadPosition() }}</p>

                    <ul class="list-group list-group-unbordered">
                    </ul>

                    <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">Makati</a></li>
                    <li><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">Cebu</a></li>
                    <li><a class="tab_pick" href="#employees_branch_tab" data-toggle="tab">Davao</a></li>
                    
                </ul>

                <div class="tab-content">

                    @include('includes.tabs.employee_tabs')

                </div>
            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.employee_modal')
    @include('includes.modals.account_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
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

        $('input, select').attr('autocomplete', 'off');
        
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
            $('#add_edit').val('add');
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

            $('#account_modal').modal('toggle');
            $('#account_modal').modal('show');

            $.ajax({
                url: '/get_account/'+id,
                dataType: 'text',
                success: function(data){
                    console.log(data);
                    $('#emp_name').val(data.employee.lname + ', ' + data.employee.fname + ' ' + data.employee.mname);
                }
            })
        })

        //ACCOUNTS -- END

        //FUNCTIONS -- END
    });
</script>

@endsection