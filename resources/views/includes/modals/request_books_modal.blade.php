<div class="modal fade" id="request_books_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Request Books</h4>
            </div>
            <form class="form-horizontal" id="add_books_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="add_edit" id="add_edit">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="request_book" class="pull-right">Book</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="request_book" name="request_book" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Book</option>
                                        @foreach($book_type as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="request_previous_pending" class="pull-right">Pending</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="request_previous_pending" name="request_previous_pending" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="request_quantity" class="pull-right">Quantity</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="request_quantity" name="request_quantity" class="form-control required" style="width: 100%;" required disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="request_pending" class="pull-right">Total Pending</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="request_pending" name="request_pending" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="request_remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="request_remarks" name="request_remarks" class="form-control" style="width: 100%;" required disabled>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_book_request">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>