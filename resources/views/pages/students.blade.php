@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_student" data-toggle="modal" data-target="#student_modal">
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
                        <li class="list-group-item">
                            <b>Followers</b> <a class="pull-right">1,322</a>
                        </li>
                        <li class="list-group-item">
                            <b>Following</b> <a class="pull-right">543</a>
                        </li>
                        <li class="list-group-item">
                            <b>Friends</b> <a class="pull-right">13,287</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#students_makati_tab" data-toggle="tab">Makati</a></li>
                    <li><a href="#students_naga_tab" data-toggle="tab">Naga</a></li>
                    <li><a href="#students_cebu_tab" data-toggle="tab">Cebu</a></li>
                    <li><a href="#students_davao_tab" data-toggle="tab">Davao</a></li>
                    
                </ul>

                

                <div class="tab-content">

                    @include('includes.tabs.student_tabs')

                </div>


                

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.student_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START
        
        $('.datepicker').datepicker();

        $('.select2').select2();

        function refresh_tables(){//reload datatable ajax
            for(var x = 0; x < 4; x++){
                students_table_variables[x].ajax.reload();
            };
        }

        $('#student_modal').on('shown.bs.modal', function(){
            $('#fname').focus();
        });

        $("#student_modal").on("hidden.bs.modal", function(e){
            $('#student_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
            $('.select2').trigger('change');
        });

        //INITIALIZE -- END


        //DATATABLES -- START

        $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
            $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        });


        var columns_students = [
            {data: 'name', name: 'name'},
            {data: 'contact', name: 'contact'},
            {data: 'program.name', name: 'program'},
            {data: 'school.name', name: 'school'},
            {data: 'benefactor.name', name: 'benefactor'},
            {data: 'gender', name: 'gender'},
            {data: 'age', name: 'age'},
            {data: 'course', name: 'course'},
            {data: 'date_of_signup', name: 'date_of_signup'},
            {data: 'referral.fname', name: 'referral'},
            {data: "action", orderable:false,searchable:false}
        ]

        const students_table_variables = [students_makati, students_naga, students_cebu, students_davao];
        const students_table_id = ['students_makati', 'students_naga', 'students_cebu', 'students_davao'];
        const students_table_route = ['/makatiStudent', 'nagaStudent', 'cebuStudent', 'davaoStudent'];

        for(var x = 0; x < 4; x++){
            students_table_variables[x] = $('#'+students_table_id[x]+ "").DataTable({
                scrollX:        true,
                scrollCollapse: true,
                fixedColumns:   true,
                ajax: students_table_route[x],
                columns: columns_students,
            });
        }
        //DATATABLES -- END

        //FUNCTIONS -- START

        $('.save_student').on('click', function(e){
            e.preventDefault();

            var input = $(this);
            var button = this;

            button.disabled = true;
            input.html('SAVING...');

            var formData = new FormData($('#student_form')[0]);
            
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/save_student',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data){
                    swal('Success!', 'Record has been saved to the Database!', 'success');
                    $('#student_modal').modal('hide');
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                    refresh_tables();
                },
                error: function(data){
                    swal("Oh no!", "Something went wrong, try again.", "error");
                    button.disabled = false;
                    input.html('SAVE CHANGES');
                }
            })
        })

        //Open Student Modal (ADD)
        $('.add_student').on('click', function(){
            $('#add_edit').val('add');
        });

        //Open Student Modal (EDIT)
        $(document).on('click', '.edit_student', function(){
            var id = $(this).attr('id');

            $.ajax({
                url: '/get_student',
                method: 'get',
                data: {id: id},
                dataType: 'json',
                success:function(data){
                    console.log(data);
                    $('#add_edit').val('edit');
                    //reserved for picture
                    $('#student_modal').modal('toggle');
                    $('#student_modal').modal('show');
                }
            });
        });

        //Delete Student
        $(document).on('click', '.delete_student', function(){
            var id = $(this).attr('id');
            console.log(id);

            swal({
                title: 'Are you sure?',
                text: 'You are about to delete a student. This may affect multiple rows',
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
                        url: '/delete_student',
                        method: 'get',
                        data: {id:id},
                        type: 'json',
                        success:function(data){
                            swal('Deleted!', 'This Student has been Deleted', 'success');
                            refresh_tables();
                        }
                    })
                }
            });
        });

        //FUNCTIONS -- END
    });
</script>

@endsection