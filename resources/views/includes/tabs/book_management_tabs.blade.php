<!-- BRANCH -- START -->

<div class="tab-pane fade in active" id="branch_tab">
    <table id="books_branch_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_branch_table')
    </table>
</div>

<!-- BRANCH -- END -->

<!-- STUDENTS -- START -->

<div class="tab-pane fade in" id="student_tab">
    <table id="books_student_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_student_table')
    </table>
</div>

<!-- STUDENTS -- END -->

<!-- BOOKS -- START -->

<div class="tab-pane fade in" id="books_tab">
    <table id="books_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_table')
    </table>
</div>

<!-- BOOKS -- END -->

<!-- REQUEST HISTORY -- START -->

<div class="tab-pane fade in" id="request_tab">
    <table id="books_request_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_request_table')
    </table>
</div>

<!-- REQUEST HISTORY -- END -->

<!-- RELEASE HISTORY -- START -->

<div class="tab-pane fade in" id="release_tab">
    <table id="books_release_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_release_table')
    </table>
</div>

<!-- RELEASE HISTORY -- END -->

<!-- ASSIGN HISTORY -- START -->

<div class="tab-pane fade in" id="assign_tab">
    <table id="books_assign_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_assign_table')
    </table>
</div>

<!-- ASSIGN HISTORY -- END -->

<!-- RETURN HISTORY-- START -->

<div class="tab-pane fade in" id="return_tab">
    <table id="books_return_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_return_table')
    </table>
</div>

<!-- RETURN HISTORY -- END -->

<!-- LOST HISTORY -- START -->

<div class="tab-pane fade in" id="lost_tab">
    <table id="books_lost_table" class="table table-hover table-striped table-bordered" cellspacing="0">
        @include('includes.tables.books.books_lost_table')
    </table>
</div>

<!-- LOST HISTORY -- END -->