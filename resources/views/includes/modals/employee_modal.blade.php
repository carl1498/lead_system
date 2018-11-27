<div class="modal fade" id="employee_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Employee</h4>
            </div>
            <form class="form-horizontal" id="employee_form">
                @csrf
                <div class="modal-body">

                    <!-- LEFT COLUMN -->
                    <div class="col-md-6">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="fname" class="pull-right">First Name</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="fname" name="fname" class="form-control required" placeholder="Enter First Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="mname" class="pull-right">Middle Name</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="mname" name="mname" class="form-control" placeholder="Enter Middle Name (Not M.I.)">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="lname" class="pull-right">Last Name</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="lname" name="lname" class="form-control required" placeholder="Enter Last Name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="birthdate" class="pull-right">Birth Date</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="birthdate" name="birthdate" class="form-control datepicker required" placeholder="MM/DD/YYYY" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="gender" class="pull-right">Gender</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="gender" name="gender" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="personal_no" class="pull-right">Personal #</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="personal_no" name="personal_no" class="form-control" placeholder="0912-345-6789">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="business_no" class="pull-right">Business #</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="personal_no" name="personal_no" class="form-control" placeholder="0912-345-6789">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="email" class="pull-right">Email</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="email" id="email" name="email" class="form-control required" placeholder="Enter Valid Email" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="picture" class="pull-right">Picture</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="file" id="picture" name="picture" class="form-control">
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-md-6">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="address" class="pull-right">Address</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="address" name="address" class="form-control required" placeholder="Enter Address" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="branch" class="pull-right">Branch</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="branch" name="branch" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Branch</option>
                                        @foreach($branch as $key => $b)
                                            <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="role" class="pull-right">Role</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="role" name="role" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Role</option>
                                        @foreach($role as $key => $r)
                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="salary" class="pull-right">Salary</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="number" id="salary" name="salary" class="form-control" placeholder="Enter Salary">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="hired" class="pull-right">Hired</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="hired" name="hired" class="form-control datepicker required" placeholder="MM/DD/YYYY Hired Date" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="sss" class="pull-right">SSS</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="sss" name="sss" class="form-control" placeholder="Enter SSS Number">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="pagibig" class="pull-right">Pagibig</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="pagibig" name="pagibig" class="form-control" placeholder="Enter Pagibig Number">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="philhealth" class="pull-right">Philhealth</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="philhealth" name="philhealth" class="form-control" placeholder="Enter Philhealth Number">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="tin" class="pull-right">TIN</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="tin" name="tin" class="form-control" placeholder="Enter TIN Number">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_student">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>