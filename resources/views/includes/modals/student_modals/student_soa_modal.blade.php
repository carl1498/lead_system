<div class="modal fade" id="soa_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 1300px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Statement of Account</h4>
                <div class="pull-right">
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Other Payment" class="btn btn-secondary bg-red add_others" style="margin-right: 20px;">
                        <i class="fa fa-plus-square"></i>
                    </button>
                </div>
            </div>
            <form class="form-horizontal" id="soa_form">
                @csrf
                <div class="modal-body clearfix">
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

                        @foreach($soa_fees_defaults as $s)
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 form-control-label">
                                <label class="pull-left">{{$s}}</label>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="number" name="amount_due[]" class="form-control soa_amount_due" placeholder="Amount Due">
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="number" name="amount_paid[]" class="form-control soa_amount_paid" placeholder="Amount Paid">
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="text" name="payment_date[]" class="form-control soa_payment_date datepicker" placeholder="Date of Payment">
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="number" name="balance[]" class="form-control soa_balance required" placeholder="Balance Due" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <select type="text" name="verified[]" class="form-control soa_verified select2" style="width: 100%;">
                                        <option value="">Verified By</option>
                                        @foreach($employee as $e)
                                            <option value="{{ $e->id }}">{{ $e->fname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 soa_width">
                                <div class="form-group">
                                    <input type="text" name="remarks[]" class="form-control soa_remarks" placeholder="Remarks">
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1">
                            </div>
                        </div>
                        @endforeach

                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 text-center font-weight-bold">
                                <h4>Others</h4>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12 soa_others">
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