<form class="form-horizontal tab-pane fade in" id="language_student_form">
    @csrf
    <div class="modal-body">
        <input type="hidden" name="l_id" id="l_id">
        <input type="hidden" name="l_add_edit" id="l_add_edit">

        <!-- LEFT COLUMN -->
        <div class="col-md-6">

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_fname" class="pull-right">First Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="l_fname" name="l_fname" class="form-control required" placeholder="Enter First Name" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_mname" class="pull-right">Middle Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="l_mname" name="l_mname" class="form-control" placeholder="Enter Middle Name (Not M.I.)">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_lname" class="pull-right">Last Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="l_lname" name="l_lname" class="form-control required" placeholder="Enter Last Name" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_birthdate" class="pull-right">Birth Date</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="l_birthdate" name="l_birthdate" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_age" class="pull-right">Age</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="number" id="l_age" name="l_age" class="form-control required" placeholder="Enter Age" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_contact" class="pull-right">Contact #</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="l_contact" name="l_contact" class="form-control required" placeholder="0912-345-6789" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_address" class="pull-right">Address</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="l_address" name="l_address" class="form-control required" placeholder="Enter Address" required>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_email" class="pull-right">Email</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="email" id="l_email" name="l_email" class="form-control required" placeholder="Enter Valid Email" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_referral" class="pull-right">Referral</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="l_referral" name="l_referral" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Referral</option>
                            @foreach($employee as $e)
                                <option value="{{ $e->id }}">{{ $e->fname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_gender" class="pull-right">Gender</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="l_gender" name="l_gender" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_branch" class="pull-right">Branch</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="l_branch" name="l_branch" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Branch</option>
                            @foreach($branch as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_course" class="pull-right">Course</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="l_course" name="l_course" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Course</option>
                            @foreach($course as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_year" class="pull-right">Year</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="l_year" name="l_year" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Year</option>
                            @foreach($departure_year as $dy)
                                <option value="{{ $dy->id }}">{{ $dy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="l_remarks" class="pull-right">Remarks</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                    <input type="text" id="l_remarks" name="l_remarks" class="form-control" placeholder="Enter Remarks">
                    </div>
                </div>
            </div>

        </div>


    </div>

    <div class="modal-footer">
        <div class="col-md-12">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary save_language_student">Save changes</button>
        </div>
    </div>
</form>