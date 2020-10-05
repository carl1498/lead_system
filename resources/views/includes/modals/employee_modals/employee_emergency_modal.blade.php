<div class="modal fade" id="emergency_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Employee Emergency Number</h4>
            </div>
            <form class="form-horizontal" id="emergency_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="e_id" id="e_id">
                    <input type="hidden" name="e_emp_id" id="e_emp_id">
                    <input type="hidden" name="e_add_edit" id="e_add_edit">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="e_fname" class="pull-right">First Name</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="e_fname" name="e_fname" class="form-control required" placeholder="First Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="e_mname" class="pull-right">Middle Name</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="e_mname" name="e_mname" class="form-control" placeholder="Enter Middle Name (Not M.I.)">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="e_lname" class="pull-right">Last Name</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="e_lname" name="e_lname" class="form-control required" placeholder="Last Name" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="e_relationship" class="pull-right">Relationship</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="e_relationship" name="e_relationship" class="form-control required" placeholder="i.e Parent, Sibling, Cousin, etc." required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="e_contact" class="pull-right">Contact #</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="e_contact" name="e_contact" class="form-control required" placeholder="0912-345-6789" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="e_address" class="pull-right">Address</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="e_address" name="e_address" class="form-control" placeholder="Address">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_emergency">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>