<div class="modal fade" id="add_books_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Books</h4>
            </div>
            <form class="form-horizontal" id="add_books_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="add_edit" id="add_edit">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="invoice_add_book" class="pull-right">Invoice Ref. No.</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="invoice_add_book" name="invoice_add_book" class="form-control select2 required" style="width: 100%;" required>
                                        <!-- Controller: invoiceController@invoiceAll -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="book_type_add_book" class="pull-right">Book</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="book_type_add_book" name="book_type_add_book" class="form-control select2 required" style="width: 100%;" required disabled>
                                        <!-- Controller: invoiceController@bookAll -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="previous_pending" class="pull-right">Pending</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="previous_pending" name="previous_pending" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="quantity" class="pull-right">Quantity</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="quantity" name="quantity" class="form-control required" style="width: 100%;" required disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="pending" class="pull-right">Total Pending</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="pending" name="pending" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="starting" class="pull-right">Starting Book No.</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="starting" name="starting" class="form-control required" style="width: 100%;" required disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="start" class="pull-right">Start</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="start" name="start" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="end" class="pull-right">End</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="end" name="end" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="remarks" name="remarks" class="form-control" style="width: 100%;" required disabled>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_books">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>