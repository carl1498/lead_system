<div class="modal fade" id="student_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Add Student</h4>
                <div class="pull-right">
                    <input id="add_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            
            <ul class="nav nav-tabs" id="student_type_tab">
                <li><a class="student_type" href="#student_form" data-toggle="tab">Student</a></li>
                <li><a class="student_type" href="#language_student_form" data-toggle="tab">Language Only</a></li>
                <li><a class="student_type" href="#ssw_student_form" data-toggle="tab">SSW</a></li>
                @if(canAccessAll())<li><a class="student_type" href="#trainee_student_form" data-toggle="tab">Trainee</a></li>@endif
            </ul>

            <div class="tab-content">
                
                @include('includes.modals.student_modals.student_forms.student_form')
                @include('includes.modals.student_modals.student_forms.language_student_form')
                @include('includes.modals.student_modals.student_forms.ssw_student_form')
                @include('includes.modals.student_modals.student_forms.trainee_student_form')

            </div>

        </div>
    </div>
</div>