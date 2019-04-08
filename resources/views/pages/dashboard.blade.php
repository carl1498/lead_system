@extends('layouts.master')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Dashboard
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-network-wired"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Your Referrals</span>
                    <span class="info-box-number referral_count">{{ $referral_count }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-user-plus"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Sign Ups</span>
                    <span class="info-box-number student_count">{{ $student_count }}</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
    </div>

    <div class="row">
        <div class="col-md-12">
          	<div class="box">
            	<div class="box-header with-border">
              		<h3 class="box-title">Monthly Recap Report</h3>

				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<div class="btn-group">
						<button type="button" class="btn btn-box-tool dropdown-toggle" data-toggle="dropdown">
							<i class="fa fa-wrench"></i></button>
						<ul class="dropdown-menu" role="menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li><a href="#">Separated link</a></li>
						</ul>
					</div>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              	</div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<div class="row">
					<div class="col-md-8">
					<p class="text-center">
						<strong>Sign Ups:</strong>
						<select class="form-control" id="sign_ups_year" style="display: inline-block; width: 90px;">
						@foreach(departure_year() as $y)
						<option value="{{ $y->id }}">{{ $y->name }}</option>
						@endforeach
						</select>
					</p>

					<div class="chart">
						<!-- Sales Chart Canvas -->
						<canvas id="salesChart" style="height: 180px;"></canvas>
					</div>
					<!-- /.chart-responsive -->
					</div>
                <!-- /.col -->
                <div class="col-md-4">
                  <p class="text-center">
                    <strong>Sign Ups per branch</strong>
                  </p>

                  <div class="progress-group">
                    <span class="progress-text">Makati</span>
                    <span class="progress-number"><b class="makati_final"></b>/<span class="makati_total"></span></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-red makati_progress"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Cebu</span>
                    <span class="progress-number"><b class="cebu_final"></b>/<span class="cebu_total"></span></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-aqua cebu_progress"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                  <div class="progress-group">
                    <span class="progress-text">Davao</span>
                    <span class="progress-number"><b class="davao_final"></b>/<span class="davao_total"></span></span>

                    <div class="progress sm">
                      <div class="progress-bar progress-bar-green davao_progress"></div>
                    </div>
                  </div>
                  <!-- /.progress-group -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
            </div>
            <!-- ./box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-md-4">

        <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <!--<img class="profile-user-img img-responsive img-circle" src="./img/avatar5.png" alt="User profile picture">-->

                    <h3 class="profile-username text-center">Birthday Month</h3>

                    <p id="birth_month" class="text-muted text-center">{{ getCurrentMonthName() }}</p>

                    <ul class="list-group list-group-unbordered">
                        @foreach($merged_birthdays as $m)
                        <li class="list-group-item">
                            <b>{{ $m->lname }}, {{ $m->fname }}</b> <p class="pull-right text-muted">{{ $m->birth_day }} ({{ $m->current_age }} y/o)</p>
                        </li>
                        @endforeach
                    </ul>

                    <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>

        <div class="col-md-3">

        <!-- Profile Image -->
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <!--<img class="profile-user-img img-responsive img-circle" src="./img/avatar5.png" alt="User profile picture">-->

                    <h3 class="profile-username text-center">Referral Leaderboard</h3>

                    <p id="birth_month" class="text-muted text-center">Highest to Lowest</p>

                    <ul class="list-group list-group-unbordered">
                        @foreach($leaderboard as $l)
                        <li class="list-group-item">
                            <b>{{ $l->referral->lname }}, {{ $l->referral->fname }}</b>
                        </li>
                        @endforeach
                    </ul>

                    <!--<a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>-->
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

</section>
@endsection

@section('script')

<script>
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

					makati_progress = (data.makati_final / data.makati) * 100;
					$('.makati_progress').css('width', makati_progress + '%');
					cebu_progress = (data.cebu_final / data.cebu) * 100;
					$('.cebu_progress').css('width', cebu_progress + '%');
					davao_progress = (data.davao_final / data.davao) * 100;
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

</script>

@endsection