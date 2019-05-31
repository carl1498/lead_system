$(document).ready(function(){
    var year, departure_month, departure_year, year_counter;
    var monthly_signup, monthly_signup_chart; //monthly signup chart

    $('.custom-checkbox').css({'visibility': 'visible', opacity: 0.0}).animate({opacity: 1.0}, 400);


    //INITIALIZE -- START
    $('body').tooltip({
        selector: '[data-toggle="tooltip"]',
        trigger : 'hover'
    });

    $('[data-toggle="tooltip"]').click(function () {
        $('[data-toggle="tooltip"]').tooltip("hide");
    });

    $.ajax({
        headers: {
            'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/get_current_year',
        method: 'get',
        dataType: 'json',
        success: function(data){
            $('#sign_ups_year').val(data);
            year = $('#sign_ups_year option:selected').text();
            departure_year = $('#departure_year_select option:selected').val();
            departure_month = $('#departure_month_select option:selected').val();
            year_counter = ($('#year_counter .toggle').hasClass('off')) ? 0 : 1;
            monthly_signup();
            branch_signups();   
        }
    });

    function monthly_signup(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/monthly_referral',
            data: {
                year: year,
                departure_year: departure_year,
                departure_month: departure_month,
                year_counter: year_counter,
            },
            method: 'get',
            dataType: 'json',
            success: function(data){
                monthly_signup = $('#salesChart');
                monthly_signup_chart = new Chart(monthly_signup, {
                    type: 'line',
                    data: {
                    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
                    datasets: [{
                        data: data.makati,
                        label: "Makati",
                        borderColor: "#DD4B39",
                        fill: false
                    }, { 
                        data: data.cebu,
                        label: "Cebu",
                        borderColor: "#00C0EF",
                        fill: false
                    }, { 
                        data: data.davao,
                        label: "Davao",
                        borderColor: "#00A65A",
                        fill: false
                    }, { 
                        data: data.all,
                        label: "All",
                        borderColor: "#f39c12",
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    tooltips: {
                    mode: 'index',
                    intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                        },
                        scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                            display: true,
                            labelString: 'Month'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                            display: true,
                            },
                        }]
                        }
                    }
                });
                
            }
        });
    }

    function update_monthly_signup(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/monthly_referral/',
            data: {
                year: year,
                departure_year: departure_year,
                departure_month: departure_month,
                year_counter: year_counter,
            },
            method: 'get',
            dataType: 'json',
            success: function(data){
                monthly_signup_chart.config.data.datasets[0].data = data.makati;
                monthly_signup_chart.config.data.datasets[1].data = data.cebu;
                monthly_signup_chart.config.data.datasets[2].data = data.davao;
                monthly_signup_chart.config.data.datasets[3].data = data.all;
                monthly_signup_chart.update();
            }
        });
        branch_signups();
    }

    function update_signup_count(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/update_signup_count',
            method: 'get',
            dataType: 'json',
            success: function(data){
                $('.referral_count').text(data.referral_count);
                $('.student_count').text(data.student_count);
            }
        });
    }
    
    /*var interval = setInterval(function(){
        update_signup_count();
        update_monthly_signup();
    }, 10000);*/
    
    function branch_signups(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/branch_signups',
            data: {
                year: year,
                departure_year: departure_year,
                departure_month: departure_month,
                year_counter: year_counter
            },
            method: 'get',
            dataType: 'json',
            success: function(data){
                var branch_class = [
                    '.makati_approved', '.makati_denied', '.makati_cancelled', '.makati_final', '.makati_active', '.makati_backout', '.makati_total',
                    '.cebu_approved', '.cebu_denied', '.cebu_cancelled', '.cebu_final', '.cebu_active', '.cebu_backout', '.cebu_total',
                    '.davao_approved', '.davao_denied', '.davao_cancelled', '.davao_final', '.davao_active', '.davao_backout', '.davao_total',
                    '.all_approved', '.all_denied', '.all_cancelled', '.all_final', '.all_active', '.all_backout', '.all_total',
                ]

                var branch_data = [
                    data.approved[0], data.denied[0], data.cancelled[0], data.final[0], data.active[0], data.backout[0], data.total[0],
                    data.approved[1], data.denied[1], data.cancelled[1], data.final[1], data.active[1], data.backout[1], data.total[1],
                    data.approved[2], data.denied[2], data.cancelled[2], data.final[2], data.active[2], data.backout[2], data.total[2],
                    data.all[0], data.all[1], data.all[2], data.all[3], data.all[4], data.all[5], data.all[6],
                ]//Approved[0], Denied[1], Cancelled[2], Final School[3], Active[4], Back Out[5], Total[6]

                for(var x = 0; x < branch_class.length; x++){
                    $(branch_class[x]).text(branch_data[x]);
                }

                var makati_progress = 0, cebu_progress = 0, davao_progress = 0, all_progress = 0;

                makati_progress = ((data.approved[0] / data.final[0]) * 100);
                cebu_progress = (data.approved[1] / data.final[1]) * 100;
                davao_progress = (data.approved[2] / data.final[2]) * 100;
                all_progress = (data.all[0] / data.all[3]) * 100;
                makati_progress = isNaN(makati_progress) ? 0 : makati_progress;
                cebu_progress = isNaN(cebu_progress) ? 0 : cebu_progress;
                davao_progress = isNaN(davao_progress) ? 0 : davao_progress;
                all_progress = isNaN(all_progress) ? 0 : all_progress;
                $('.makati_progress').css('width', makati_progress + '%');
                $('.cebu_progress').css('width', cebu_progress + '%');
                $('.davao_progress').css('width', davao_progress + '%');
                $('.all_progress').css('width', all_progress + '%');
            }
        });
    }

    //INITIALIZE -- END

    //DATATABLES -- START

    //DATATABLES -- END

    //FUNCTIONS -- START

    $(document).on('change', '#sign_ups_year, #departure_year_select, #departure_month_select, #year_counter .toggle', function(){
        year = $('#sign_ups_year option:selected').text();
        departure_year = $('#departure_year_select option:selected').val();
        departure_month = $('#departure_month_select option:selected').val();
        year_counter = ($('#year_counter .toggle').hasClass('off')) ? 0 : 1;
        update_monthly_signup();
    });

    //FUNCTIONS -- END
});