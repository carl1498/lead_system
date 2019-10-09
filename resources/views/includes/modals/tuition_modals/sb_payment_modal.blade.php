<div class="modal fade" id="sb_payment_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Security Bond</h4>
                <div class="pull-right">
                    <input id="sb_payment_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <form class="form-horizontal" id="sb_payment_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" id="s_id" name="s_id">
                    <input type="hidden" id="s_add_edit" name="s_add_edit">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="s_student" class="pull-right">Student</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="s_student" name="s_student" class="form-control select2" style="width: 100%;" required>
                                        <option value="" disabled selected>Select Student</option>
                                            @foreach($student as $s)
                                            <option value="{{ $s->id }}">{{ $s->fname }} {{ $s->lname }} @if($s->program)({{ $s->program->name }}))@endif</option>
                                            @endforeach
                                        <!-- Controller: tuitionController@get_tf_student -->
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_amount" class="pull-right">Amount</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" id="s_amount" step="0.01" name="s_amount" class="form-control required" placeholder="Enter Amount" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_date" class="pull-right">Date</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="s_date" name="s_date" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="s_remarks" name="s_remarks" class="form-control" placeholder="Enter Remarks">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_sb_payment">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>