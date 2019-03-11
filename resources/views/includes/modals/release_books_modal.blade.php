<div class="modal fade" id="release_books_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Release Books</h4>
            </div>
            <form class="form-horizontal" id="release_books_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="add_edit" id="add_edit">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_branch" class="pull-right">Branch</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="release_branch" name="release_branch" class="form-control select2 required" style="width: 100%;" required>
                                        <!-- Controller: releaseBooksController@get_branch -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_book" class="pull-right">Book</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <select type="text" id="release_book" name="release_book" class="form-control select2 required" style="width: 100%;" required disabled>
                                        <!-- Controller: releaseBooksController@get_books -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_stocks" class="pull-right">Stocks On Hand</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="release_stocks" name="release_stocks" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_previous_pending" class="pull-right">Pending</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="release_previous_pending" name="release_previous_pending" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_quantity" class="pull-right">Quantity</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="release_quantity" name="release_quantity" class="form-control required" style="width: 100%;" required disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_pending" class="pull-right">Total Pending</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="release_pending" name="release_pending" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_starting" class="pull-right">Starting No.</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="release_starting" name="release_starting" class="form-control required" style="width: 100%;" required disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_start" class="pull-right">Start</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="release_start" name="release_start" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_end" class="pull-right">End</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group required">
                                    <input type="number" min="0" id="release_end" name="release_end" class="form-control required" style="width: 100%;" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-control-label">
                                <label for="release_remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                <div class="form-group">
                                    <input type="text" id="release_remarks" name="release_remarks" class="form-control" style="width: 100%;" disabled>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_book_release">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>