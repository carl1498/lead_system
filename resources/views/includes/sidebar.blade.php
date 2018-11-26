<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- Sidebar user panel -->
			<div class="user-panel">
				<div class="pull-left image">
					<img src="./img/avatar5.png" class="img-circle" alt="User Image">
				</div>
        		<div class="pull-left info">
					<p>{{ onLoadName() }}</p>
					<p>{{ onLoadPosition() }}</p>
        		</div>
      		</div>
			<!-- sidebar menu: : style can be found in sidebar.less -->
			<ul class="sidebar-menu" data-widget="tree">
				<li class="header">MAIN NAVIGATION</li>
				
				<li class="{{ (Request::path() == 'dashboard') ? 'active' : '' }}">
					<a href="/dashboard" >
						<i class="fa fa-tachometer-alt"></i> <span>Dashboard</span>
						<span class="pull-right-container">
						</span>
					</a>
				</li>
		
                <li class="{{ (Request::path() == 'students' || Request::path() == 'schools' || Request::path() == 'programs' || Request::path() == 'benefactors') ? 'active' : '' }} treeview">
					<a href="">
						<i class="fa fa-user-graduate"></i> <span>Students</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="{{ (Request::path() == 'students') ? 'active' : '' }}"><a href="/students"><i class="fa fa-list-ul"></i> Student List</a></li>
						<li class="{{ (Request::path() == 'schools') ? 'active' : '' }}"><a href="/schools"><i class="fa fa-graduation-cap"></i> Schools</a></li>
						<li class="{{ (Request::path() == 'programs') ? 'active' : '' }}"><a href="/programs"><i class="fa fa-clipboard-list"></i> Programs</a></li>
						<li class="{{ (Request::path() == 'benefactors') ? 'active' : '' }}"><a href="/benefactors"><i class="fa fa-hands-helping"></i> Benefactors</a></li>
					</ul>
				</li>

				<li class="{{ (Request::path() == 'employees') ? 'active' : '' }}">
					<a href="/employees">
						<i class="fa fa-address-card"></i> <span>Employee Management</span>
						<span class="pull-right-container">
						</span>
					</a>
				</li>

                <li>
					<a href="#">
						<i class="fa fa-file-alt"></i> <span>Documentation</span>
						<span class="pull-right-container">
						</span>
					</a>
				</li>
      		</ul>
    	</section>
    	<!-- /.sidebar -->
</aside>