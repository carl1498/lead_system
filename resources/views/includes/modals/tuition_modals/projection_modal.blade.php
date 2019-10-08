<div class="modal fade" id="projection_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Projected Fees</h4>
            </div>
            <form class="form-horizontal" id="projection_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="prog_id" id="prog_id">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label class="pull-right">Sign Up</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label class="pull-right">Visa Processing</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label class="pull-right">Language</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label class="pull-right">Documentation</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label class="pull-right">Selection</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="proj_amount" class="pull-right">PDOS</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="proj_amount" class="pull-right">Airfare</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <input type="hidden" class="proj_name_id" name="proj_name_id[]">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="proj_amount" class="pull-right">DHL</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="number" name="proj_amount[]" class="proj_amount form-control required" placeholder="Amount" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" name="proj_date[]" class="proj_date form-control" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="text" name="proj_remarks[]" class="proj_remarks form-control" placeholder="Remarks">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_projection">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>