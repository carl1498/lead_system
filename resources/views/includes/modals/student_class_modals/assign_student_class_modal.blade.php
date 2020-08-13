<div class="modal fade" id="assign_student_class_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Assign Student's Class</h4>
                <div class="pull-right">
                    <input id="assign_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <form class="form-horizontal" id="assign_student_class_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <div class="pretty p-icon p-pulse">
                                    <input type="checkbox" class="completeCheck" />
                                    <div class="state p-success">
                                        <i class="icon fa fa-check"></i>
                                        <label>Include Complete Classes</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"></div>
                        </div>
                        <br>
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                    <label for="sensei_class" class="pull-right">Sensei</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group required">
                                    <select type="text" id="sensei_class" name="sensei_class" class="form-control select2 required" style="width: 100%;" required>
                                        <!-- Controller: studentClassController@sensei_class -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-4 form-control-label">
                                    <label for="date_class" class="pull-right">Class</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-4 col-xs-4">
                                <div class="form-group required">
                                    <select type="text" id="date_class" name="date_class" class="form-control select2 required" style="width: 100%;" disabled required>
                                        <!-- Controller: studentClassController@date_class -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="student_class" class="pull-right">Student</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <select type="text" id="student_class" name="student_class" class="form-control select2 required" style="width: 100%;" disabled required>
                                        <!-- Controller: studentClassController@student_class -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="current_student_class" class="pull-right">Student Class</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <input type="text" id="current_student_class" name="current_student_class" class="form-control" placeholder="Student's Current Class" 
                                    data-container="body" data-toggle="tooltip" data-placement="top" title="If student is currently assigned to an on-going class"
                                    style="width: 100%;" readonly>
                                    <input type="hidden" name="class_students_id" id="class_students_id">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                    <label for="current_end_date" class="pull-right">End Date</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group required">
                                    <input type="text" id="current_end_date" name="current_end_date" class="form-control datepicker required" placeholder="End Date: YYYY-MM-DD" 
                                    data-container="body" data-toggle="tooltip" data-placement="top" title="End the student's on-going class(if any)"
                                    style="width: 100%;" disabled required>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_assign">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>