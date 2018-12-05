@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Student Settings Page
    </h1>
    <ol class="breadcrumb">
        <li>
            <button class="btn btn-secondary bg-red add_student_settings">
                <i class="fa fa-plus-square"></i>
            </button>
        </li>
    </ol>
</section>

<!-- Main content -->
<section class="content">

    <div class="row">
        <!-- /.col -->
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Program</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">School</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Benefactor</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Year</a></li>
                    <li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Month</a></li>
                    <!--<li><a class="settings_pick" href="#student_settings_tab" data-toggle="tab">Course</a></li>-->
                </ul>

                <div class="tab-content">
                    
                    @include('includes.tabs.student_settings_tabs')

                </div>

            </div>
        </div>
    </div>

    <!-- MODALS -- START -->
    
    @include('includes.modals.student_settings_modal')

    <!-- MODALS -- END -->

</section>
@endsection

@section('script')
<script>
    $(document).ready(function(){

        //INITIALIZE -- START

        var current_settings = 'Program';
        var student_settings;

        $('#student_settings_modal').on('shown.bs.modal', function(){
            $('#fname').focus();
        });

        $("#student_settings_modal").on("hidden.bs.modal", function(e){
            $('#student_settings_form :input.required').each(function (){
                this.style.setProperty('border-color', 'green', 'important');
            });
            $(this).find("input,textarea,select").val('').end();
        });

        //INITIALIZE -- END


        //DATATABLES -- START

        function refresh_student_settings(){
            student_settings = $('#student_settings').DataTable({
                destroy: true,
                autoWidth: true,
                scrollCollapse: true,
                ajax: {
                    url: '/view_student_settings',
                    data: {current_settings: current_settings}
                },
                columns: [
                    {data: 'name', name: 'name'},
                    {data: 'action', orderable: false, searchable: false}
                ]
            });
        }

        refresh_student_settings();

        //DATATABLES -- END

        //FUNCTIONS -- START

        //Clicking on tabs
        $('.settings_pick').on('click', function(){
            current_settings = $(this).text();

            refresh_student_settings();
        });

        //Add or Edit Settings
        $('.add_student_settings').on('click', function(){
            $('#student_settings_modal').modal('toggle');
            $('#student_settings_modal').modal('show');

            $('#student_settings_modal .modal-title').text('mao ni');
        });


        //FUNCTIONS -- END
    });
</script>

@endsection