<div class="modal fade" id="bulk_salary_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 850px;">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Bulk Add Salary</h4>
                <div class="pull-right">
                    <input id="bulk_salary_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <form class="form-horizontal" id="bulk_salary_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 form-control-label">
                                    <label for="b_cutoff" class="pull-right">Cutoff</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="b_cutoff_from" name="b_cutoff_from" class="form-control datepicker required" placeholder="From: YYYY-MM-DD" required>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="b_cutoff_to" name="b_cutoff_to" class="form-control datepicker required" placeholder="To: YYYY-MM-DD" required>
                                </div>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 form-control-label">
                                    <label for="b_release" class="pull-right">Release</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="b_release" name="b_release" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-3 col-xs-3 form-control-label">
                                    <label for="b_position" class="pull-right">Position</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <select type="text" id="b_position" class="form-control select2" style="width: 100%;">
                                        <option value="All">All</option>
                                        @foreach($role as $r)
                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3"></div>
                            <div class="col-lg-1 col-md-1 col-sm-3 col-xs-3 form-control-label">
                                    <label for="b_status" class="pull-right">Status</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <select type="text" id="b_status" class="form-control select2" style="width: 100%;">
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
                                    <label for="b_emp" class="pull-right">Employees</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="b_emp" name="b_emp[]" multiple="multiple" class="form-control select2 required" style="width: 100%;" required>
                                        <!--<option value="">Select Employees</option>-->
                                        @foreach($emp_salary as $e)
                                            <option value="{{ $e->employee->id }}">{{ $e->employee->lname }}, {{ $e->employee->fname }} {{ $e->employee->mname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-6 col-sm-3 col-xs-3 form-control-label">
                                    <label for="b_basic_days" class="pull-right">Days Rendered</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="b_basic_days" name="b_basic_days" class="form-control required" placeholder="Days" required>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-3">

                        <div class="row clearfix">
                            <div class="col-lg-1 col-md-1 col-sm-7 col-xs-7"></div>
                            <div class="col-lg-6 col-md-6 col-sm-7 col-xs-7">
                                <div class="form-group">
                                <label><input type="checkbox" name="allowance_counter" id="allowance_counter"> Allowance</label>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="b_wfh" class="pull-right">WFH</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" step="any" id="b_wfh" name="b_wfh" class="form-control" placeholder="Enter WFH in % (1-100)">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary bulk_save_salary">SAVE CHANGES</button>
                </div>
            </form>
        </div>
    </div>
</div>