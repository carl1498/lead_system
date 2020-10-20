<div class="modal fade" id="soa_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 1200px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Statement of Account</h4>
                <div class="pull-right">
                    <input id="emp_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <form class="form-horizontal" id="soa_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="soa_id" id="soa_id">
                    <input type="hidden" name="soa_stud_id" id="soa_stud_id">
                    <input type="hidden" name="soa_add_edit" id="soa_add_edit">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3">
                                <label class="text-center">Payment Description</label>
                            </div>
                            <div class="col-lg-1 col-md-1 text-center soa_width">
                                <label>Amount Due</label>
                            </div>
                            <div class="col-lg-1 col-md-1 text-center soa_width">
                                <label>Amount Paid</label>
                            </div>
                            <div class="col-lg-1 col-md-1 text-center soa_width">
                                <label>Payment Date</label>
                            </div>
                            <div class="col-lg-1 col-md-1 text-center soa_width">
                                <label>Balance Due</label>
                            </div>
                            <div class="col-lg-1 col-md-1 text-center soa_width">
                                <label>Verified By</label>
                            </div>
                            <div class="col-lg-1 col-md-1 text-center soa_width">
                                <label>Remarks</label>
                            </div>
                        </div>

                        <br>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 form-control-label">
                                <label class="pull-left">Daily Living Allowance</label>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="text" name="test" class="form-control" placeholder="Amount Due" required>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="text" name="test" class="form-control" placeholder="Amount Paid" required>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="text" name="test" class="form-control datepicker" placeholder="Date of Payment" required>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="text" name="test" class="form-control required" placeholder="Balance Due" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <select type="text" name="verified[]" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Verified By</option>
                                        @foreach($employee as $e)
                                            <option value="{{ $e->id }}">{{ $e->fname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="text" name="test" class="form-control" placeholder="Remarks" required>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_soa">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>