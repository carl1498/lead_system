$(document).ready(function(){
    
    //INITIALIZE -- START

    var dayCheck, completeCheck = false, sensei, class_select, student;
    var current_class_tab = 'Ongoing';//Ongoing, Complete, all
    var current_class_select;

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

    $('.select2').select2();

    $('#date_class').select2({
        placeholder: 'Select Class'
    });

    $('#student_class').select2({
        placeholder: 'Select Student'
    });

    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $('input, select').attr('autocomplete', 'off');
    
    $("#add_class_modal").on("hidden.bs.modal", function(e){
        $('#add_class_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        }); 
        $(this).find(".add_start_time, .add_end_time, #start_date, #end_date, #remarks, select").val('').end();
        $(this).find(".add_start_time, .add_end_time").prop('readonly', true);
        $('.addCheck').prop('checked', false);
        $('.select2').trigger('change.select2');
    });
    
    $("#assign_student_class_modal").on("hidden.bs.modal", function(e){
        $('#assign_student_class_form :input.required').each(function (){
            this.style.setProperty('border-color', 'green', 'important');
        }); 
        $(this).find(".select2, input").val('').end();
        $(this).find("#date_class, #student_class").prop('disabled', true);
        $('.completeCheck').prop('checked', false);
        completeCheck = false;
        $('.select2').trigger('change.select2');
    });

    //INITIALIZE -- END
    
    //DATATABLES -- START

    $('#student_class_table').DataTable({stateSave: true,
        stateSaveCallback: function(settings,data) {
            localStorage.setItem( 'DataTables_' + settings.sInstance, JSON.stringify(data) )
        },
        stateLoadCallback: function(settings) {
            return JSON.parse( localStorage.getItem( 'DataTables_' + settings.sInstance ) )
        },
        initComplete: function(settings, json) {
        },
        processing: true,
        destroy: true,
        scrollX: true,
        scrollCollapse: true,
        fixedColumns: true,
        responsive: true,
    });

    //DATATABLES -- END
    
    //FUNCTIONS -- START

    $('.addCheck').change(function(){
        dayCheck = $(this).next().val();
        startTimeInput = document.getElementsByClassName('add_start_time')[dayCheck-1];
        endTimeInput = document.getElementsByClassName('add_end_time')[dayCheck-1];
        if($(this).is(':checked')){
            $([startTimeInput, endTimeInput]).prop('readonly', false);
        }else{
            $([startTimeInput, endTimeInput]).prop('readonly', true);
            $([startTimeInput, endTimeInput]).val('');
        }
    });
    
    //Open Add Class Modal
    $('.add_class').on('click', function(){
        $('#add_class_modal').modal('toggle');
        $('#add_class_modal').modal('show');
    });

    $(document).on('submit', '#add_class_form', function(e){
        e.preventDefault();

        var input = $('.save_class');
        var button = document.getElementsByClassName("save_class")[0];

        button.disabled = true;
        input.html('SAVING...');

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/add_class',
            method: 'POST',
            data: $('#add_class_form').serialize(),
            success: function(data){
                $('#add_class_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
            },
            error: function(data){
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    //Open Assign Student Class Modal
    $('.assign_student_class').on('click', function(){
        $('#assign_student_class_modal').modal('toggle');
        $('#assign_student_class_modal').modal('show');
    });

    function load_classes(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/get_class/'+current_class_tab,
            method: 'get',
            dataType: 'json',
            success: function(data){

                $('#class_box').empty();

                let html = '';

                html += '<ul class="list-group list-group-unbordered">';

                for(let x = 0; x < data.class_settings.length; x++){
                    let counter = true;
                    let days = '';
                    for(let y = 0; y < 6; y++){
                        if(data.class_settings[x].class_day[y].start_time){
                            if(counter == false){
                                days += ' • ';
                            }
                            days += `<span data-container="body" data-toggle="tooltip" 
                            data-placement="top"
                            title="` + data.class_settings[x].class_day[y].start_time.name + ' ~ ' + 
                            ((data.class_settings[x].class_day[y].end_time) ? data.class_settings[x].class_day[y].end_time.name : 'TBD') + `">` +
                            data.class_settings[x].class_day[y].day_name.abbrev + `</span>`;

                            counter = false;
                        }
                    }
                    html += `
                    <li class="list-group-item class_pick" id="`+data.class_settings[x].id+`">
                        <p class="class_get_id" style="word-wrap: break-word;">`+
                            data.class_settings[x].start_date + ' ~ ' + ((data.class_settings[x].end_date) ? data.class_settings[x].end_date : 'TBD') + '<br>'+
                            '<b>' + data.class_settings[x].sensei.fname + ' ' + data.class_settings[x].sensei.lname + '</b><br>'+
                            '<span style="cursor:help;">' + days +
                        `</p>
                    </li>`
                }

                html += '</ul>';

                $('#on_going_class_box span').text(data.on_going);
                $('#complete_class_box span').text(data.completed);
                $('#all_class_box span').text(data.all);
                $('#class_box').append(html);
            }
        });
    }

    load_classes();
    
    $('.completeCheck').change(function(){
        $('#sensei_class, #date_class, #current_student_class, #class_students_id, #current_end_date').val('').trigger('change');
        $("#date_class, #student_class, #current_end_date").prop('disabled', true);

        if($(this).is(':checked')){
            completeCheck = true;
            getSenseiClass();
        }else{
            completeCheck = false;
            getSenseiClass();
        }
    });

    $('#sensei_class').change(function(){
        $('#date_class, #student_class, #current_student_class, #class_students_id, #current_end_date').val('').trigger('change');
        $("#date_class").prop('disabled', false);
        $("#student_class, #current_end_date").prop('disabled', true);
        sensei = $(this).val();

        getDateClass();
    })

    $('#date_class').change(function(){
        class_select = $(this).val();
        if($(this).val()){
            $('#student_class, #current_student_class, #class_students_id, #current_end_date').val('').trigger('change');
            $('#student_class').val('').prop('disabled', false);
            $("#current_end_date").prop('disabled', true);
        }

        getStudentClass();
    });

    $('#student_class').change(function(){
        if($(this).val()){
            student = $(this).val();
    
            $.ajax({
                url: '/check_student_class/'+student,
                method: 'get',
                dataType: 'json',
                success : function(data){
                    if(data != false){
                        $('#current_student_class').val(data.current_class.start_date + ' ~ ' +
                        ((data.current_class.end_date) ? data.current_class.end_date : 'TBD') +
                        ' (' + data.current_class.sensei.fname + ' ' + data.current_class.sensei.lname + ')');
                        $('#current_end_date').val('').prop('disabled', false);
                        $('#class_students_id').val(data.id);
                    }else{
                        $('#current_student_class').val('No Current Class');
                        $('#current_end_date').val('').prop('disabled', true);
                        $('#class_students_id').val('');
                    }
                }
            });
        }
    });

    $(document).on('submit', '#assign_student_class_form', function(e){
        e.preventDefault();

        var input = $('.save_assign');
        var button = document.getElementsByClassName("save_assign")[0];

        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/assign_student_class',
            data: $(this).serialize(),
            dataType: 'text',
            success:function(data){
                $('#assign_student_class_modal').modal('hide');
                notif('Success!', 'Record has been saved to the Database!', 'success', 'glyphicon-ok');
                button.disabled = false;
                input.html('SAVE CHANGES');
            },
            error: function(data){
                swal("Error!", "Something went wrong, try again.", "error");
                button.disabled = false;
                input.html('SAVE CHANGES');
            }
        });
    });

    function getSenseiClass(){
        $('#sensei_class').select2({
            placeholder: 'Select Sensei',
            ajax: {
                url: '/senseiClass/'+completeCheck,
                dataType: 'json',

                data: function (params){
                    return {
                        name: params.term,
                        page: params.page
                    }
                },
                processResults: function (data){
                    return {
                        results:data.results
                    }
                }
            },
        });
    }

    function getDateClass(){
        $('#date_class').select2({
            placeholder: 'Select Class',
            ajax: {
                url: '/dateClass',
                dataType: 'json',

                data: function (params){
                    return {
                        completeCheck: completeCheck,
                        sensei: sensei,
                        name: params.term,
                        page: params.page
                    }
                },
                processResults: function (data){
                    return {
                        results:data.results
                    }
                }
            },
        });
    }

    function getStudentClass(){
        $('#student_class').select2({
            placeholder: 'Select Student',
            ajax: {
                url: '/studentClass',
                dataType: 'json',

                data: function (params){
                    return {
                        name: params.term,
                        page: params.page
                    }
                },
                processResults: function (data){
                    return {
                        results:data.results
                    }
                }
            },
        });
    }

    getSenseiClass();

    //CLASS BOX -- START

    $(document).on('click', '.class_pick', function(){
        if(current_class_select){
            $('.class_pick'+'#'+current_class_select).css('background-color', '');
        }

        $(this).css('background-color', '#4AB19D');
        current_class_select = $(this).attr('id');
    });

    $(document).on('click', '#on_going_class_box', function(){
        if(!$(this).hasClass('disabled')){
            current_class_tab = 'ongoing';
            load_classes();
        }
    });

    $(document).on('click', '.class_nav_box', function(){
        if(!$(this).hasClass('disabled')){
            $('.class_nav_box').attr('disabled', false);
            $(this).attr('disabled', true);
            current_class_tab = $(this).find('b').text();
            load_classes();
        }
    });

    //CLASS BOX -- END

    //FUNCTIONS -- END
});