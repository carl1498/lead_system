<!-- STUDENTS BRANCH -- START -->

<div class="tab-pane fade in active" id="students_branch_tab">
    
    <table id="students_branch" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.students.student_table')
    </table>

</div>

<!-- STUDENTS BRANCH -- END -->

<!-- STUDENTS FINAL SCHOOL / BACK OUT -- START -->

<div class="tab-pane fade in" id="students_status_tab">
    
    <table id="students_status" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.students.student_table_status')
    </table>
    
</div>

<!-- STUDENTS FINAL SCHOOL / BACK OUT  -- END -->

<!-- STUDENTS RESULT MONITORING -- START -->

<div class="tab-pane fade in" id="students_result_tab">
    
    <table id="students_result" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.students.student_table_result')
    </table>
    
</div>

<!-- STUDENTS RESULT MONITORING -- END -->

<!-- LANGUAGE ONLY STUDENTS -- START -->

<div class="tab-pane fade in" id="language_student_tab">
    
    <table id="language_students" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.students.student_table_language')
    </table>
    
</div>

<!-- LANGUAGE ONLY STUDENTS -- END -->

<!-- ALL STUDENTS -- START -->

<div class="tab-pane fade in" id="all_student_tab">
    
    <table id="all_students" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.students.student_table_all')
    </table>
    
</div>

<!-- ALL STUDENTS -- END -->

<!-- SSV STUDENTS -- START -->

<div class="tab-pane fade in" id="ssv_student_tab">
    
    <table id="ssv_students" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.students.student_table_ssv')
    </table>
    
</div>

<!-- SSV STUDENTS -- END -->

<!-- SSV BACKOUT -- START -->

<div class="tab-pane fade in" id="ssv_backout_tab">
    
    <table id="ssv_backout" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.students.student_table_ssv')
    </table>
    
</div>

<!-- SSV BACKOUT -- END -->