<div class="modal fade" id="salary_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 1000px;">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Salary</h4>
            </div>
            <form class="form-horizontal" id="salary_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="s_id" id="s_id">


                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2"></div>
                            <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 form-control-label">
                                    <label for="cutoff" class="pull-right">Cutoff</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="cutoff_from" name="cutoff_from" class="form-control datepicker required" placeholder="From: YYYY-MM-DD" required>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="cutoff_to" name="cutoff_to" class="form-control datepicker required" placeholder="To: YYYY-MM-DD" required>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 form-control-label">
                                    <label for="release" class="pull-right">Release</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="release" name="release" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 form-control-label">
                                    <label for="position" class="pull-right">Position</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <select type="text" id="position" class="form-control select2" style="width: 100%;">
                                        <option value="All">All</option>
                                        @foreach($role as $r)
                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3"></div>
                            <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 form-control-label">
                                    <label for="status" class="pull-right">Status</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <select type="text" id="status" class="form-control select2" style="width: 100%;">
                                        <option value="Active">Active</option>
                                        <option value="Resigned">Resigned</option>
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 form-control-label">
                                    <label for="emp" class="pull-right">Employee</label>
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="emp" name="emp" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="" >Select Employee</option>
                                        @foreach($emp_salary as $e)
                                            <option value="{{ $e->employee->id }}">{{ $e->employee->lname }}, {{ $e->employee->fname }} {{ $e->employee->mname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="text" id="s_branch" name="s_branch" class="form-control required" placeholder="Branch" required readonly>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="text" id="s_company" name="s_company" class="form-control required" placeholder="Company" required readonly>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <div class="col-md-12" style="margin-top: 10px;">

                        <div class="col-md-4">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                        <label for="s_rate" class="pull-right">Rate</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                    <div class="form-group">
                                        <input type="number" step="any" id="s_rate" name="s_rate" class="form-control required" placeholder="Enter Rate" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                        <label for="s_daily" class="pull-right">Daily</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                    <div class="form-group">
                                        <input type="number" step="any" id="s_daily" name="s_daily" class="form-control required" placeholder="Enter Daily" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="row clearfix">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_type" class="pull-right">Type</label>
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                    <div class="form-group required">
                                        <select type="text" id="s_type" name="s_type" class="form-control select2 required" style="width: 100%;" required>
                                            <option value="">Select Type</option>
                                            <option value="Monthly">Monthly</option>
                                            <option value="Daily">Daily</option>
                                            <option value="Yen">Yen</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6" style="margin-top: 10px;">

                        <div class="row clearfix">
                            <div class="text-center col-lg-12 col-md-12 col-sm-12 col-xs-12 form-control-label">
                                <label>INCOME</label>
                            </div>
                        </div>

                        <br>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="basic_days" class="pull-right">Basic Rate</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="basic_days" name="basic_days" class="form-control required" placeholder="Days" required>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="basic_amount" name="basic_amount" class="form-control required" placeholder="Basic Rate" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_accom" class="pull-right">Accommodation</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="s_accom" name="s_accom" class="form-control" placeholder="Enter Accommodation">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_cola" class="pull-right">COLA</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="s_cola" name="s_cola" class="form-control" placeholder="Enter COLA">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="transpo_days" class="pull-right">Transporation</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="transpo_days" name="transpo_days" class="form-control" placeholder="Days">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="s_transpo" name="s_transpo" class="form-control" placeholder="Amount" style="padding-right: 0;">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="s_transpo_amount" name="s_transpo_amount" class="form-control" placeholder="Total" readonly style="padding-right: 0;">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="mktg_comm" class="pull-right">Mktg Commission</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="mktg_comm" name="mktg_comm" class="form-control" placeholder="Enter Marketing Commission">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="jap_comm" class="pull-right">Japan Commission</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="jap_comm" name="jap_comm" class="form-control" placeholder="Enter Japan Commission">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="reg_ot_hours" class="pull-right">Reg. OT</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="reg_ot_hours" name="reg_ot_hours" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="reg_ot_amount" name="reg_ot_amount" class="form-control" placeholder="Reg. OT Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="rd_ot_hours" class="pull-right">RD OT</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="rd_ot_hours" name="rd_ot_hours" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="rd_ot_amount" name="rd_ot_amount" class="form-control" placeholder="RD OT Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="thirteenth" class="pull-right">13th Month</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="thirteenth" name="thirteenth" class="form-control" placeholder="Enter 13th Month">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="leg_hol_hours" class="pull-right">Leg Holiday</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="leg_hol_hours" name="leg_hol_hours" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="leg_hol_amount" name="leg_hol_amount" class="form-control" placeholder="Leg Hol Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="spcl_hol_hours" class="pull-right">Spcl Holiday</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="spcl_hol_hours" name="spcl_hol_hours" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="spcl_hol_amount" name="spcl_hol_amount" class="form-control" placeholder="Spcl Hol Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="leg_hol_ot_hours" class="pull-right">Leg Holiday OT</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="leg_hol_ot_hours" name="leg_hol_ot_hours" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="leg_hol_ot_amount" name="leg_hol_ot_amount" class="form-control" placeholder="Leg Hol OT Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="spcl_hol_ot_hours" class="pull-right">Spcl Holiday OT</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="spcl_hol_ot_hours" name="spcl_hol_ot_hours" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="spcl_hol_ot_amount" name="spcl_hol_ot_amount" class="form-control" placeholder="Spcl Hol OT Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="adjustments" class="pull-right">Adjustments</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="adjustments" name="adjustments" class="form-control" placeholder="Enter Adjustments">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="gross" class="pull-right">Gross Pay</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="gross" name="gross" class="form-control required" placeholder="Gross Pay" required readonly>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-6" style="margin-top: 10px;">
                    
                        <div class="row clearfix">
                            <div class="text-center col-lg-12 col-md-12 col-sm-12 col-xs-12 form-control-label">
                                    <label>DEDUCTIONS</label>
                            </div>
                        </div>
                        
                        <br>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="cash_advance" class="pull-right">Cash Advance</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="cash_advance" name="cash_advance" class="form-control" placeholder="Enter Cash Advance">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="absence_days" class="pull-right">Absent</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="absence_days" name="absence_days" class="form-control" placeholder="Days">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="absence_amount" name="absence_amount" class="form-control" placeholder="Absent Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="lete_hours" class="pull-right">Late</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="late_hours" name="late_days" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="late_amount" name="late_amount" class="form-control" placeholder="Late Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_sss" class="pull-right">SSS</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="s_sss" name="s_sss" class="form-control" placeholder="Enter SSS">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_phic" class="pull-right">Philhealth</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="s_phic" name="s_phic" class="form-control" placeholder="Enter Philhealth">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="s_hdmf" class="pull-right">Pag-IBIG</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="s_hdmf" name="s_hdmf" class="form-control" placeholder="Enter Pag-IBIG">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="others" class="pull-right">Others</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="others" name="others" class="form-control" placeholder="Enter Others">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="basic" class="pull-right">Undertime</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="under_hours" name="under_days" class="form-control" placeholder="Hours">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="under_amount" name="under_amount" class="form-control" placeholder="Undertime Amount" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="tax" class="pull-right">Tax</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="tax" name="tax" class="form-control" placeholder="Enter Tax">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="man_allocation" class="pull-right">Mandatory Allocation</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="man_allocation" name="man_allocation" class="form-control" placeholder="Enter Man. Allocation">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="wfh" class="pull-right">WFH</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="wfh" name="wfh" class="form-control" placeholder="%">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="wfh_amount" name="wfh_amount" class="form-control" placeholder="WFH Amount" style="padding-right: 0;" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="deduction" class="pull-right">Total Deduction</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="deduction" name="deduction" class="form-control required" placeholder="Total Deduction" required readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group" style="visibility: hidden;">
                                    <input type="number" class="form-control" placeholder="Blank" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group" style="visibility: hidden;">
                                    <input type="number" class="form-control" placeholder="Blank" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-5 col-md-5 col-sm-3 col-xs-3 form-control-label">
                                    <label for="net" class="pull-right">Net Pay</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="net" name="net" class="form-control required" placeholder="Net Pay" required readonly>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary save_salary">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>