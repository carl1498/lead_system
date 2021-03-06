<div class="modal fade" id="edit_employee_history_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Employment History</h4>
            </div>
            <form class="form-horizontal" id="edit_employee_history_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">
                        <input type="hidden" name="eh_id" id="eh_id">

                        <div class="row clearfix">

                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="edit_hired_date" class="pull-right">Hired Date</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="edit_hired_date" name="edit_hired_date" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">

                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="edit_until" class="pull-right">Until</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="edit_until" name="edit_until" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
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