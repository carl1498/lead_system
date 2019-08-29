<div class="modal fade" id="client_bank_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Bank - <span class="title_bank"></span></h4>
            </div>
            <form class="form-horizontal" id="client_bank_form" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="b_client_id" id="b_client_id">
                    <input type="hidden" name="b_id" id="b_id">
                    <input type="hidden" name="b_add_edit" id="b_add_edit">

                    <!-- LEFT COLUMN -->
                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="bank_name" class="pull-right">Bank Name</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="bank_name" name="bank_name" class="form-control required" placeholder="Enter Bank Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="swift_code" class="pull-right">Swift Code</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="swift_code" name="swift_code" class="form-control required" placeholder="Enter Swift Code" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="bank_branch_name" class="pull-right">Branch Name</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="bank_branch_name" name="bank_branch_name" class="form-control required" placeholder="Enter Branch Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="bank_address" class="pull-right">Address</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="bank_address" name="bank_address" class="form-control required" placeholder="Enter Address" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="account_name" class="pull-right">Account Name</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="account_name" name="account_name" class="form-control required" placeholder="Enter Account Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="account_number" class="pull-right">Account No.</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="account_number" name="account_number" class="form-control required" placeholder="Enter Account Number" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="bank_contact" class="pull-right">Contact</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="bank_contact" name="bank_contact" class="form-control" placeholder="Enter Contact #">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_client_bank">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>