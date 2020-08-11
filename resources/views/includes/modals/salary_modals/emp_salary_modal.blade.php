<div class="modal fade" id="emp_salary_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Employee Salary - 
                    <span class="emp_name"></span> [<span class="emp_branch"></span>] [<span class="emp_company"></span>]</h4>
            </div>
            <form class="form-horizontal" id="emp_salary_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="id" id="id">
                    
                    <div class="col-md-12">

                        <div class="col-md-4">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                        <label for="rate" class="pull-right">Rate</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                    <div class="form-group">
                                        <input type="text" id="rate" name="rate" class="form-control required" placeholder="Enter Rate" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                        <label for="daily" class="pull-right">Daily</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                    <div class="form-group">
                                        <input type="text" id="daily" name="daily" class="form-control required" placeholder="Enter Daily" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="type" class="pull-right">Type</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                    <div class="form-group required">
                                        <select type="text" id="type" name="type" class="form-control select2 required" style="width: 100%;" required>
                                            <option value="">Select Type</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Daily">Daily</option>
                                            <option value="Daily">Yen</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <br><br><br>

                    <div class="col-md-6">

                        <div class="row clearfix">
                            <div class="text-center col-lg-12 col-md-12 col-sm-12 col-xs-12 form-control-label">
                                <label>ALLOWANCES</label>
                            </div>
                        </div>

                        <br>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="cola" class="pull-right">COLA</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="cola" name="cola" class="form-control" placeholder="Enter COLA">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="accom" class="pull-right">Accommodation</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="accom" name="accom" class="form-control" placeholder="Enter Accommodation">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="transpo" class="pull-right">Transporation</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="transpo" name="transpo" class="form-control" placeholder="Enter Transporation">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6">
                    

                        <div class="row clearfix">
                            <div class="text-center col-lg-12 col-md-12 col-sm-12 col-xs-12 form-control-label">
                                    <label>BENEFITS</label>
                            </div>
                        </div>
                        
                        <br>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="sss" class="pull-right">SSS</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="sss" name="sss" class="form-control" placeholder="Enter SSS">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="phic" class="pull-right">Philhealth</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="phic" name="phic" class="form-control" placeholder="Enter Philhealth">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="hdmf" class="pull-right">Pag-IBIG</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="hdmf" name="hdmf" class="form-control" placeholder="Enter Philhealth">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save_emp_salary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>