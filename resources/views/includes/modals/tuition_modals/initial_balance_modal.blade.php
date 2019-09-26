<div class="modal fade" id="initial_balance_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left"></h4>
            </div>
            <form class="form-horizontal" id="initial_balance_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" id="p_id" name="p_id">
                    <input type="hidden" id="add_edit" name="add_edit">
                    <input type="hidden" id="p_type" name="p_type">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="p_amount" class="pull-right">Amount</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" id="p_amount" min="0.00" name="p_amount" class="form-control required" placeholder="Enter Amount" required readonly>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_tf_sb_payment">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>