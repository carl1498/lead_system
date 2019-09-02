<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
		<!-- sidebar: style can be found in sidebar.less -->
		<section class="sidebar">
			<!-- Sidebar user panel -->
			<div class="user-panel">
				<div class="pull-left image">
					<img src="./storage/img/employee/{{ picture() }}" class="img-rounded" alt="User Image">
				</div>
        		<div class="pull-left info">
					<p>{{ onLoadName() }}</p>
					<p style="font-size: 11px;">{{ onLoadPosition() }}</p>
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
                <li class="{{ (Request::path() == 'students' || Request::path() == 'classes' || 
					Request::path() == 'student_settings' || Request::path() == 'student_add_history' || 
					Request::path() == 'student_edit_history' || Request::path() == 'student_delete_history') ? 'active' : '' }} treeview">
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
						@if(canAccessAll() || canAccessStudents())
						<li class="{{ (Request::path() == 'classes') ? 'active' : '' }}"><a href="/classes"><i class="fa fa-chalkboard-teacher"></i> Student Class</a></li>
						@endif
						@if(canAccessAll() || canAccessStudentSettings())
						<li class="{{ (Request::path() == 'student_settings') ? 'active' : '' }}"><a href="/student_settings"><i class="fa fa-cog"></i> Settings</a></li>
						@endif
						@if(canAccessAll() || canAccessStudentList())
						<li class="{{ (Request::path() == 'student_add_history' || Request::path() == 'student_edit_history' ||
										Request::path() == 'student_delete_history') ? 'active' : '' }} treeview">
							<a href="">
								<i class="fa fa-history"></i> <span>Student Logs</span>
								<span class="pull-right-container">
									<i class="fa fa-angle-left pull-right"></i>
								</span>
							</a>
							<ul class="treeview-menu">
								<li class="{{ (Request::path() == 'student_add_history') ? 'active' : '' }}"><a href="/student_add_history"><i class="fa fa-plus"></i> Add History</a></li>
								<li class="{{ (Request::path() == 'student_edit_history') ? 'active' : '' }}"><a href="/student_edit_history"><i class="fa fa-edit"></i> Edit History</a></li>
								<li class="{{ (Request::path() == 'student_delete_history') ? 'active' : '' }}"><a href="/student_delete_history"><i class="fa fa-trash"></i> Delete History</a></li>
							</ul>
						</li>
						@endif
					</ul>
				</li>
				@endif

				@if(canAccessAll() || canAccessExpense())
                <li class="{{ (Request::path() == 'expense') ? 'active' : '' }} treeview">
					<a href="">
						<i class="fa fa-calculator"></i> <span>Finance</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="{{ (Request::path() == 'expense') ? 'active' : '' }}"><a href="/expense"><i class="fa fa-money-bill-alt"></i> Expense</a></li>
						<!--<li class=""><a href="/students"><i class="fa fa-cash-register"></i> Tuition Fee</a></li>
						<li class=""><a href="/student_settings"><i class="fa fa-network-wired"></i> Referral Monitoring</a></li>
						<li class=""><a href="/student_settings"><i class="fa fa-wallet"></i> Allowance Monitoring</a></li>-->
					</ul>
				</li>
				@endif

				@if(canAccessAll() || canAccessClient())
                <li class="{{ (Request::path() == 'client' || Request::path() == 'order') ? 'active' : '' }} treeview">
					<a href="">
						<i class="fa fa-building"></i> <span>Clients</span>
						<span class="pull-right-container">
							<i class="fa fa-angle-left pull-right"></i>
						</span>
					</a>
					<ul class="treeview-menu">
						<li class="{{ (Request::path() == 'client') ? 'active' : '' }}"><a href="/client"><i class="fa fa-user-tie"></i> Client List</a></li>
						<li class="{{ (Request::path() == 'order') ? 'active' : '' }}"><a href="/order"><i class="fa fa-hand-holding"></i> Orders</a></li>
					</ul>
				</li>
				@endif

				@if(canAccessAll() || canAccessEmployees())
				<li class="{{ (Request::path() == 'employees') ? 'active' : '' }}">
					<a href="/employees" >
						<i class="fa fa-address-card"></i> <span>Employees</span>
					</a>
				</li>
				@endif

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

				<li>
					<a id="logout_button" role="button">
						<i class="fa fa-power-off text-red"></i> <span class="text-red">Logout</span>
					</a>
				</li>
      		</ul>
    	</section>
    	<!-- /.sidebar -->
</aside>