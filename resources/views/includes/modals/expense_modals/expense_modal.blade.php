<div class="modal fade" id="expense_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Expense</h4>
                <div class="pull-right">
                    <input id="expense_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <form class="form-horizontal" id="expense_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">
                        <input type="hidden" name="e_id" id="e_id">
                        <input type="hidden" name="e_add_edit" id="e_add_edit">

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                <label for="expense_type" class="pull-right">Type</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <select type="text" id="expense_type" name="expense_type" class="form-control select2" style="width: 100%;" required>
                                        <option value="" disabled selected>Select Expense Type</option>
                                        @foreach($expense_type as $et)
                                            <option value="{{ $et->id }}">{{ $et->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                <label for="expense_particular" class="pull-right">Particular</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group required">
                                    <select type="text" id="expense_particular" name="expense_particular" class="form-control select2" style="width: 100%;" required>
                                        <option value="" disabled selected>Select Expense Particular</option>
                                        @foreach($expense_particular as $ep)
                                            <option value="{{ $ep->id }}">{{ $ep->name }} @if($ep->address)({{ $ep->address }})@endif</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                <label for="branch" class="pull-right">Branch</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <select type="text" id="branch" name="branch" class="form-control select2" style="width: 100%;" required>
                                        <option value="" disabled selected>Select Branch</option>
                                        @foreach($branch as $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                <label for="company_type" class="pull-right">Company</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <select type="text" id="company_type" name="company_type" class="form-control select2" style="width: 100%;" required>
                                        <option value="" disabled selected>Select Company Type</option>
                                        @foreach($lead_company_type as $lct)
                                            <option value="{{ $lct->id }}">{{ $lct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    <label for="date" class="pull-right">Date</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <input type="text" id="date" name="date" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    <label for="amount" class="pull-right">Amount</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <input type="number" id="amount" step="0.01" name="amount" class="form-control required" placeholder="Enter Amount" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                <label for="vat" class="pull-right">VAT</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <select type="text" id="vat" name="vat" class="form-control select2" style="width: 100%;" required>
                                        <option value="" disabled selected>Select VAT / NON-VAT</option>
                                        <option value="NON-VAT">NON-VAT</option>
                                        <option value="VAT">VAT</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    <label for="input_tax" class="pull-right">Input Tax</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group"  data-container="body" data-toggle="tooltip" data-placement="top" 
                                    title="Will automatically compute if expense is VAT" >
                                    <input type="number" id="input_tax" name="input_tax" class="form-control required" placeholder="Input Tax" required readonly>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    <label for="check_voucher" class="pull-right">Voucher</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group">
                                    <input type="text" id="check_voucher" name="check_voucher" class="form-control" placeholder="Enter Check Voucher">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    <label for="remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group">
                                    <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Enter Remarks">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_expense">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>