<div class="modal fade" id="assign_student_class_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Assign Student's Class</h4>
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
                                        <!-- Controller: studentClassController@sensei_all -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                    <label for="date_class" class="pull-right">Class</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group required">
                                    <select type="text" id="date_class" name="date_class" class="form-control select2 required" style="width: 100%;" required>
                                        <!-- Controller: studentClassController@get_student -->
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_class">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>