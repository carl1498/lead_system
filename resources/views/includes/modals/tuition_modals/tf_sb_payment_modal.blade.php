<div class="modal fade" id="tf_sb_payment_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left"></h4>
                <div class="pull-right">
                    <input id="tf_sb_payment_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <form class="form-horizontal" id="tf_sb_payment_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" id="p_id" name="p_id">
                    <input type="hidden" id="add_edit" name="add_edit">
                    <input type="hidden" id="p_type" name="p_type">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="p_student" class="pull-right">Student</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="p_student" name="p_student" class="form-control select2" style="width: 100%;" required>
                                        <option value="" disabled selected>Select Student</option>
                                            @foreach($student as $s)
                                            <option value="{{ $s->id }}">{{ $s->student->fname }} {{ $s->student->lname }} ({{ $s->student->program->name }})</option>
                                            @endforeach
                                        <!-- Controller: tuitionController@get_tf_student -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="current" class="pull-right">Current</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" id="current" min="0.00" name="current" class="form-control required" placeholder="Current" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="p_prev_amount" class="pull-right">Previous Amt.</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" id="p_prev_amount" min="0.00" name="p_prev_amount" class="form-control required" placeholder="Previous Amount" required readonly>
                                </div>
                            </div>
                        </div>

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

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="total" class="pull-right">Total</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" id="total" min="0.00" name="total" class="form-control required" placeholder="Total" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="date" class="pull-right">Date</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="date" name="date" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
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
                        <button type="submit" class="btn btn-primary save_tf_sb_payment">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>