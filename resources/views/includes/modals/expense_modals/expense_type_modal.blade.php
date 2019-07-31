<div class="modal fade" id="expense_type_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Type</h4>
            </div>
            <form class="form-horizontal" id="expense_type_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">
                        <input type="hidden" name="t_id" id="t_id">
                        <input type="hidden" name="t_add_edit" id="t_add_edit">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="expense_type_name" class="pull-right">Name</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <div class="form-group">
                                    <input type="text" id="expense_type_name" name="expense_type_name" class="form-control required" placeholder="Input Expense Type" required>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_expense_type">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>