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

				@if(canAccessAll() || canAccessStudents())
                <li class="{{ (Request::path() == 'students' || Request::path() == 'student_settings') ? 'active' : '' }} treeview">
					<a href="">
						<i class="fa fa-user-graduate"></i> <span>Students</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						@if(canAccessAll() || canAccessStudentList())
						<li class="{{ (Request::path() == 'students') ? 'active' : '' }}"><a href="/students"><i class="fa fa-list-ul"></i> Student List</a></li>
						@endif
						@if(canAccessAll() || canAccessStudentSettings())
						<li class="{{ (Request::path() == 'student_settings') ? 'active' : '' }}"><a href="/student_settings"><i class="fa fa-cog"></i> Settings</a></li>
						@endif
					</ul>
				</li>
				@endif

				@if(canAccessAll() || canAccessEmployees())
				<li class="{{ (Request::path() == 'employees') ? 'active' : '' }}">
					<a href="/employees" >
						<i class="fa fa-address-card"></i> <span>Employees</span>
						<span class="pull-right-container">
						</span>
					</a>
				</li>
				@endif

                <!--<li>
					<a href="#">
						<i class="fa fa-file-alt"></i> <span>Documentation</span>
						<span class="pull-right-container">
						</span>
					</a>
				</li>-->

				@if(canAccessAll() || canAccessBooks())
				<li class="{{ (Request::path() == 'invoice' || Request::path() == 'book_management') ? 'active' : '' }} treeview">
					<a href="">
						<i class="fa fa-book"></i> <span>Books</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						@if(canAccessAll() || canAccessInvoice())
						<li class="{{ (Request::path() == 'invoice') ? 'active' : '' }}"><a href="/invoice"><i class="fa fa-file-invoice"></i> Invoice</a></li>
						@endif
						@if(canAccessAll() || canAccessBookManagement())
						<li class="{{ (Request::path() == 'book_management') ? 'active' : '' }}"><a href="/book_management"><i class="fa fa-book-open"></i> Book Management</a></li>
						@endif
					</ul>
				</li>
				@endif
      		</ul>
    	</section>
    	<!-- /.sidebar -->
</aside>