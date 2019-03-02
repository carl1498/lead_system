<div class="modal fade" id="student_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Student</h4>
            </div>
            
            <ul class="nav nav-tabs" id="student_type_tab">
                <li><a class="student_type" href="#student_form" data-toggle="tab">Student</a></li>
                <li><a class="student_type" href="#language_student_form" data-toggle="tab">Language Only</a></li>
            </ul>

            <div class="tab-content">
                
                @include('includes.modals.forms.student_form')
                @include('includes.modals.forms.language_student_form')

            </div>

        </div>
    </div>
</div>