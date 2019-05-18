<div class="modal fade" id="invoice_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Invoice</h4>
            </div>
            <form class="form-horizontal" id="invoice_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 form-control-label">
                                <label for="invoice_ref_no" class="pull-right">Invoice Ref. No.</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" id="invoice_ref_no" name="invoice_ref_no" class="form-control required" placeholder="Invoice Reference Number" required>
                                </div>
                            </div>
                        </div>

                        <!-- LEFT COLUMN -->
                        <div class="col-md-6">

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-control-label">
                                    <label for="book_1" class="pull-right">Minna no Nihongo I</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="book_1" name="book_1" class="form-control" placeholder="Student">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-control-label">
                                    <label for="wb_1" class="pull-right">Work Book I</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="wb_1" name="wb_1" class="form-control" placeholder="Student">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-control-label">
                                    <label for="book_2" class="pull-right">Minna no Nihongo II</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="book_2" name="book_2" class="form-control" placeholder="Student">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-control-label">
                                    <label for="wb_2" class="pull-right">Work Book II</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="wb_2" name="wb_2" class="form-control" placeholder="Student">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 form-control-label">
                                    <label for="kanji" class="pull-right">Kanji</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="kanji" name="kanji" class="form-control" placeholder="Student">
                                    </div>
                                </div>
                            </div>
                        
                        </div>

                        <!-- RIGHT COLUMN -->
                        <div class="col-md-6">

                            <div class="row clearfix">
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 form-control-label">
                                    <label for="book_1_ssv" class="pull-right">Minna no Nihongo I</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="book_1_ssv" name="book_1_ssv" class="form-control" placeholder="SSV">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 form-control-label">
                                    <label for="wb_1_ssv" class="pull-right">Work Book I</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="wb_1_ssv" name="wb_1_ssv" class="form-control" placeholder="SSV">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 form-control-label">
                                    <label for="book_2_ssv" class="pull-right">Minna no Nihongo II</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="book_2_ssv" name="book_2_ssv" class="form-control" placeholder="SSV">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 form-control-label">
                                    <label for="wb_2_ssv" class="pull-right">Work Book II</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="wb_2_ssv" name="wb_2_ssv" class="form-control" placeholder="SSV">
                                    </div>
                                </div>
                            </div>

                            <div class="row clearfix">
                                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 form-control-label">
                                    <label for="kanji_ssv" class="pull-right">Kanji</label>
                                </div>
                                <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
                                    <div class="form-group">
                                        <input type="number" id="kanji_ssv" name="kanji_ssv" class="form-control" placeholder="SSV">
                                    </div>
                                </div>
                            </div>
                        
                        </div>

                        

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_invoice">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>