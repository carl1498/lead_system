<div class="modal fade" id="educational_background_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Educational Background</h4>
            </div>
            <form class="form-horizontal" id="educational_background_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="eb_id" id="eb_id">
                    <input type="hidden" name="eb_emp_id" id="eb_emp_id">
                    <input type="hidden" name="eb_add_edit" id="eb_add_edit">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pe_company" class="pull-right">Company</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="pe_company" name="pe_company" class="form-control required" placeholder="Company Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pe_address" class="pull-right">Address</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="pe_address" name="pe_address" class="form-control required" placeholder="Blg No., Street, City, Province, Zip" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pe_hired_date" class="pull-right">Hired Date</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="pe_hired_date" name="pe_hired_date" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pe_until" class="pull-right">Until</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="pe_until" name="pe_until" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pe_salary" class="pull-right">Salary</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="number" id="pe_salary" name="pe_salary" class="form-control" placeholder="Monthly Wage">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pe_designation" class="pull-right">Desgination</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="pe_designation" name="pe_designation" class="form-control required" placeholder="Designation/Position" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pe_employment_type" class="pull-right">Employment Type</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="pe_employment_type" name="pe_employment_type" class="form-control required" placeholder="Regular/Contractual/etc." required>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_prev_employment">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>