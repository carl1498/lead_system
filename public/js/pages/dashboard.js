$(document).ready(function(){
    var year;
    var monthly_signup, monthly_signup_chart; //monthly signup chart

    //INITIALIZE -- START

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
            monthly_signup();
            branch_signups();
        }
    });

    function monthly_signup(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/monthly_referral/'+year,
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
            url: '/monthly_referral/'+year,
            method: 'get',
            dataType: 'json',
            success: function(data){
                monthly_signup_chart.config.data.datasets[0].data = data.makati;
                monthly_signup_chart.config.data.datasets[1].data = data.cebu;
                monthly_signup_chart.config.data.datasets[2].data = data.davao;
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
    
    var interval = setInterval(function(){
        update_signup_count();
        update_monthly_signup();
    }, 10000);
    
    function branch_signups(){
        $.ajax({
            headers: {
                'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/branch_signups/'+year,
            method: 'get',
            dataType: 'json',
            success: function(data){
                $('.makati_total').text(data.makati);
                $('.cebu_total').text(data.cebu);
                $('.davao_total').text(data.davao);
                $('.makati_final').text(data.makati_final);
                $('.cebu_final').text(data.cebu_final);
                $('.davao_final').text(data.davao_final);

                var makati_progress = 0, cebu_progress = 0, davao_progress = 0;

                makati_progress = ((data.makati_final / data.makati) * 100);
                cebu_progress = (data.cebu_final / data.cebu) * 100;
                davao_progress = (data.davao_final / data.davao) * 100;
                makati_progress = isNaN(makati_progress) ? 0 : makati_progress;
                cebu_progress = isNaN(cebu_progress) ? 0 : cebu_progress;
                davao_progress = isNaN(davao_progress) ? 0 : davao_progress;
                $('.makati_progress').css('width', makati_progress + '%');
                $('.cebu_progress').css('width', cebu_progress + '%');
                $('.davao_progress').css('width', davao_progress + '%');
            }
        });
    }

    //INITIALIZE -- END

    //DATATABLES -- START

    //DATATABLES -- END

    //FUNCTIONS -- START

    $(document).on('change', '#sign_ups_year', function(){
        year = $('#sign_ups_year option:selected').text();
        update_monthly_signup();
        branch_signups();
    });

    //FUNCTIONS -- END
});