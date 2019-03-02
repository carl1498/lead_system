<!-- STUDENTS BRANCH -- START -->

<div class="tab-pane fade in active" id="students_branch_tab">
    
    <table id="students_branch" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.student_table')
    </table>

</div>

<!-- STUDENTS BRANCH -- END -->

<!-- STUDENTS FINAL SCHOOL / BACK OUT -- START -->

<div class="tab-pane fade in" id="students_status_tab">
    
    <table id="students_status" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.student_table_status')
    </table>
    
</div>

<!-- STUDENTS FINAL SCHOOL / BACK OUT  -- END -->

<!-- STUDENTS RESULT MONITORING -- START -->

<div class="tab-pane fade in" id="students_result_tab">
    
    <table id="students_result" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.student_table_result')
    </table>
    
</div>

<!-- STUDENTS RESULT MONITORING -- END -->

<!-- LANGUAGE ONLY STUDENTS -- START -->

<div class="tab-pane fade in" id="language_student_tab">
    
    <table id="language_students" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.language_student_table')
    </table>
    
</div>

<!-- LANGUAGE ONLY STUDENTS -- END -->