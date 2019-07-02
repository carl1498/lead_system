$(document).ready(function(){
    
    //INITIALIZE -- START

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

    var dayCheck, completeCheck = false;

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

    $('.completeCheck').change(function(){
        $('#sensei_class').val('').trigger('change');
        $('#date_class').val('').trigger('change');

        if($(this).is(':checked')){
            completeCheck = true;
            getClassSelect();
        }else{
            completeCheck = false;
            getClassSelect();
        }
    })

    $('.select2').select2();

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
            url: '/get_class',
            method: 'get',
            success: function(data){
                //$('#on-going_box').html('');

                let html = '';

                html += '<ul class="list-group list-group-unbordered">';

                for(let x = 0; x < data.length; x++){
                    let counter = true;
                    let days = '';
                    for(let y = 0; y < 6; y++){
                        if(data[x].class_day[y].start_time){
                            if(counter == false){
                                days += ' • ';
                            }
                            days += `<span data-container="body" data-toggle="tooltip" 
                            data-placement="top"
                            title="` + data[x].class_day[y].start_time.name + ' ~ ' + 
                            ((data[x].class_day[y].end_time) ? data[x].class_day[y].end_time.name : 'TBD') + `">` +
                            data[x].class_day[y].day_name.abbrev + `</span>`;

                            /*+ data[x].class_day[y].start_time + ` ~ ` +
                            (data[x].class_day[y].end_time) ? data[x].class_day[y].end_time : 'TBD' + `*/

                            counter = false;
                        }
                    }
                    html += `
                    <li class="list-group-item">
                        <p style="word-wrap: break-word;">`+
                            data[x].start_date + ' ~ ' + ((data[x].end_date) ? data[x].end_date : 'TBD') + '<br>'+
                            '<b>' + data[x].sensei.fname + ' ' + data[x].sensei.lname + '</b><br>'+
                            '<span style="cursor:help;">' + days +
                        `</p>
                    </li>`
                }

                html += '</ul>';

                $('#on-going_box').append(html);
            }
        });
    }

    load_classes();

    function getClassSelect(){
        $('#sensei_class').select2({
            placeholder: 'Select Sensei',
            ajax: {
                url: '/senseiClassAll/'+completeCheck,
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

    getClassSelect();

    //FUNCTIONS -- END
});