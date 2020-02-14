<div class="modal fade" id="emp_history_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Previous Employment History</h4>
            </div>
            <form class="form-horizontal" id="emp_history_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="eh_id" id="eh_id">
                    <input type="hidden" name="eh_stud_id" id="eh_stud_id">
                    <input type="hidden" name="eh_add_edit" id="eh_add_edit">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="eh_company" class="pull-right">Company</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="eh_company" name="eh_company" class="form-control required" placeholder="Company Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="eh_position" class="pull-right">Position</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="eh_position" name="eh_position" class="form-control required" placeholder="Designation/Position" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="eh_started" class="pull-right">Started</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="eh_started" name="eh_started" class="form-control required" placeholder="Enter Date Started" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="eh_finished" class="pull-right">Finished</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="eh_finished" name="eh_finished" class="form-control required" placeholder="Enter Date Finished" required>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_employment_history">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>