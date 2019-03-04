<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Lead System</title>

	<link rel="shortcut icon" type="image/png" href="./img/favicon.png"/>
	<link rel="stylesheet" type="text/css" href="/css/datatables-bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/fixedColumns-bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/css/bootstrap-datepicker.min.css">
	<link rel="stylesheet" type="text/css" href="/css/select2.min.css">
	<link rel="stylesheet" type="text/css" href="/css/app.css">
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
					<li class="dropdown user user-menu">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<img src="./img/avatar5.png" class="user-image" alt="User Image">
							<span class="hidden-xs">{{ onLoadName() }}</span>
						</a>
						<ul class="dropdown-menu">
							<!-- User image -->
							<li class="user-header">
								<img src="./img/avatar5.png" class="img-circle" alt="User Image">
								<p>
									{{ onLoadName() }} - {{ onLoadPosition() }}
									<small>Member since {{ Auth::user()->created_at }}</small>
								</p>
							</li>
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="#" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="/logout" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>
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

  	<footer class="main-footer">
		<div class="pull-right hidden-xs">
			<b>Version</b> 2.4.0
		</div>
    	<strong>Copyright &copy; 2014-2016 <a href="https://adminlte.io">Almsaeed Studio</a>.</strong> All rights
    	reserved.
  	</footer>
</div>
<!-- ./wrapper -->
<script src="/js/jquery.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/sweetalert2-all.min.js"></script>
<script src="/js/jquery-datatables.min.js"></script>
<script src="/js/datatables-bootstrap.min.js"></script>
<script src="/js/datatables-fixedColumns.min.js"></script>
<script src="/js/bootstrap-datepicker.min.js"></script>
<script src="/js/select2-full.min.js"></script>
<script src="/js/app.js"></script>
@yield('script')
</body>
</html>
