<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<title>Lead System</title>

	<link rel="shortcut icon" type="image/png" href="./img/favicon.png"/>
	<link rel="stylesheet" type="text/css" href="/css/datatables-bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/fixedColumns-bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" type="text/css" href="/css/animate.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.bootstrap.min.css"><link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="{{ mix('/css/app.css') }}">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  	<header class="main-header">
		<!-- Logo -->
		<a href="index2.html" class="logo" style="background-color: #ae0c07">
			<!-- mini logo for sidebar mini 50x50 pixels -->
			<span class="logo-mini"><img src="./img/tree.png" alt="lead logo" style="width: 40px; height: 40px;"></span>
			<!-- logo for regular state and mobile devices -->
			<span class="logo-lg"><img src="./img/tree.png" alt="lead logo" style="width: 40px; height: 40px;"><b>LEAD</b></span>
		</a>

		<!-- Header Navbar: style can be found in header.less -->
		<nav class="navbar navbar-static-top" style="background-color: #ae0c07">
			<!-- Sidebar toggle button-->
			<a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
				<span class="sr-only">Toggle navigation</span>
			</a>
			<!-- Navbar Right Menu -->
			<div class="navbar-custom-menu">
				<ul class="nav navbar-nav">
					<!-- User Account: style can be found in dropdown.less -->
					<!--<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<span class="hidden-xs">{{ onLoadName() }}</span>
						</a>
						<ul class="dropdown-menu" style="width: 100px;">
							User image
							<li class="user-header">
								<img src="./img/avatar5.png" class="img-circle" alt="User Image">
								<p>
									{{ onLoadName() }} - {{ onLoadPosition() }}
									<small>Member since {{ Auth::user()->created_at }}</small>
								</p>
							</li>
							Menu Footer
							<li class="user-footer">
								<div class="pull-left">
									<a href="#" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="/logout" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>-->
				</ul>
			</div>
		</nav>
  	</header>
	@include('includes.sidebar')

	<!-- Content Wrapper. Contains page content -->
	<div class="content-wrapper">
		@yield('content')
    <!-- /.content -->
  	</div>
  	<!-- /.content-wrapper -->

  	<!--<footer class="main-footer">
		<div class="pull-right hidden-xs">
			<b>Version</b> 2.4.0
		</div>
    	<strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    	reserved.
	</footer>-->
	<!--Icons made by https://www.flaticon.com/authors/smashicons Smashicons from https://www.flaticon.com/" Flaticon www.flaticon.com is licensed by http://creativecommons.org/licenses/by/3.0/ Creative Commons BY 3.0 CC 3.0 BY-->

	
</div>
<!-- ./wrapper -->
<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/sweetalert2-all.min.js"></script>
<script src="/js/jquery-datatables.min.js"></script>
<script src="/js/datatables-bootstrap.min.js"></script>
<script src="/js/datatables-fixedColumns.min.js"></script>
<script src="/js/bootstrap-datepicker.min.js"></script>
<script src="/js/bootstrap-notify.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0/dist/Chart.min.js"></script>
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
<script src="{{ mix('/js/app.js') }}"></script>
@yield('script')
<script>
	function notif(title, message, type, glyphicon){
		$.notify({
			icon: 'glyphicon '+glyphicon,
			title: title,
			message: message,
		},{
			type:type,
			delay: 2000,
			animate: {
				enter: 'animated fadeInDown',
				exit: 'animated fadeOutUp'
			},
			allow_dismiss: true,
			placement: {
				from: "top",
				align: "right"
			},
			offset: 1,
			z_index: 3000,
		});
	}
</script>
</body>
</html>
