<div class="modal fade" id="edit_student_date_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 300px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Student Date</h4>
            </div>
            <form class="form-horizontal" id="edit_student_date_form">
                @csrf
                <div class="modal-body">

                    <div class="col-md-12">
                        <input type="hidden" id="edit_student_class_id" name="edit_student_class_id">
                        
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="student_name_temp" class="pull-right">Name</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group">
                                    <input type="text" id="student_name_temp" name="student_name_temp" class="form-control" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="e_student_start_date" class="pull-right">Start</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group">
                                    <input type="text" id="e_student_start_date" name="e_student_start_date" class="form-control datepicker required" placeholder="Start Date: YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="e_student_end_date" class="pull-right">End</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group">
                                    <input type="text" id="e_student_end_date" name="e_student_end_date" class="form-control datepicker" placeholder="End Date: YYYY-MM-DD">
                                </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_edit_student_date">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>