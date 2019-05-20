<!-- EMPLOYEES ALL -- START -->

<div class="tab-pane fade in active" id="employees_all_tab">
    <table id="employees_all" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.employee_all_table')
    </table>
</div>

<!-- EMPLOYEES ALL -- END -->

<!-- EMPLOYEES MAKATI -- START -->

<div class="tab-pane fade in" id="employees_branch_tab">
    <table id="employees_branch" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.employee_table')
    </table>
</div>

<!-- EMPLOYEES MAKATI -- END -->

<!-- EMPLOYEES CEBU -- START -->

<div class="tab-pane fade in" id="employees_cebu_tab">
    <table id="employees_cebu" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.employee_table')
    </table>
</div>

<!-- EMPLOYEES CEBU -- END -->

<!-- EMPLOYEES DAVAO -- START -->

<div class="tab-pane fade in" id="employees_davao_tab">
    <table id="employees_davao" class="table table-striped table-bordered" cellspacing="0">
        @include('includes.tables.employee_table')
    </table>
</div>

<!-- EMPLOYEES DAVAO -- END -->