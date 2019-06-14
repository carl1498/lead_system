<!-- EMPLOYEES ALL -- START -->

<div class="tab-pane fade in active" id="employees_all_tab">
    <table id="employees_all" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        @include('includes.tables.employee_all_table')
    </table>
</div>

<!-- EMPLOYEES ALL -- END -->

<!-- EMPLOYEES BRANCH -- START -->

<div class="tab-pane fade in" id="employees_branch_tab">
    <table id="employees_branch" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        @include('includes.tables.employee_table')
    </table>
</div>

<!-- EMPLOYEES BRANCH -- END -->