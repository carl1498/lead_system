<div class="modal fade" id="rehire_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Employee Rehiring</h4>
            </div>
            <form class="form-horizontal" id="rehire_form">
                @csrf
                <div class="modal-body clearfix">

                    <!-- LEFT COLUMN -->
                    <div class="col-md-12">

                        <div class="row clearfix">
                            <input type="hidden" name="rh_id" id="rh_id">

                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="rehiring_date" class="pull-right">Rehired Date</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="rehiring_date" name="rehiring_date" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_rehire_employee">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>