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
                    <input type="hidden" id="i_id" name="i_id">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="init_balance" class="pull-right">Balance</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" id="init_balance" min="0.00" name="init_balance" class="form-control required" placeholder="Enter Initial Balance" required>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_initial_balance">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>