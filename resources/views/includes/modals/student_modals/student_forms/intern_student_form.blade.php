<form class="form-horizontal tab-pane fade in" id="intern_student_form">
    @csrf
    <div class="modal-body clearfix">
        <input type="hidden" name="i_id" id="i_id">
        <input type="hidden" name="i_add_edit" id="i_add_edit">

        <!-- LEFT COLUMN -->
        <div class="col-md-6">

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_fname" class="pull-right">First Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_fname" name="i_fname" class="form-control required" placeholder="Enter First Name" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_mname" class="pull-right">Middle Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_mname" name="i_mname" class="form-control" placeholder="Enter Middle Name (Not M.I.)">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_lname" class="pull-right">Last Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_lname" name="i_lname" class="form-control required" placeholder="Enter Last Name" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_branch" class="pull-right">Branch</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="i_branch" name="i_branch" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Branch</option>
                            @foreach($branch as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_university" class="pull-right">University</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <select type="text" id="i_university" name="i_university" class="form-control select2" style="width: 100%;">
                            <option value="" disabled selected>Select University</option>
                            @foreach($university as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_benefactor" class="pull-right">Benefactor</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <select type="text" id="i_benefactor" name="i_benefactor" class="form-control select2" style="width: 100%;">
                            <option value="" disabled selected>Select Benefactor</option>
                            @foreach($benefactor as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_contact" class="pull-right">Contact #</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_contact" name="i_contact" class="form-control required" placeholder="0912-345-6789" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_gender" class="pull-right">Gender</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="i_gender" name="i_gender" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_birthdate" class="pull-right">Birth Date</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_birthdate" name="i_birthdate" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_civil" class="pull-right">Civil Status</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="i_civil" name="i_civil" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Civil Status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Separated">Separated</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_course" class="pull-right">Course</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="i_course" name="i_course" class="form-control select2 required" style="width: 100%;" required>
                            <option value="" disabled selected>Select Course</option>
                            @foreach($course as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_email" class="pull-right">Email</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="email" id="i_email" name="i_email" class="form-control required" placeholder="Enter Valid Email" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_address" class="pull-right">Address</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_address" name="i_address" class="form-control required" placeholder="Blg No., Street, City, Province, Zip" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_referral" class="pull-right">Referral</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="i_referral" name="i_referral" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Referral</option>
                            @foreach($employee as $e)
                                <option value="{{ $e->id }}">{{ $e->fname }} {{ $e->lname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_sign_up" class="pull-right">Sign Up</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_sign_up" name="i_sign_up" class="form-control datepicker required" placeholder="YYYY-MM-DD Date of Sign Up" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_medical" class="pull-right">Medical</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_medical" name="i_medical" class="form-control datepicker" placeholder="YYYY-MM-DD Date of Medical">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_completion" class="pull-right">Completion</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="i_completion" name="i_completion" class="form-control datepicker" placeholder="YYYY-MM-DD Date of Completion">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_year" class="pull-right">Year</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="i_year" name="i_year" class="form-control select2" style="width: 100%;" required>
                            <option value="">Select Departure Year</option>
                            @foreach($departure_year as $dy)
                                <option value="{{ $dy->id }}">{{ $dy->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_month" class="pull-right">Month</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="i_month" name="i_month" class="form-control select2" style="width: 100%;" required>
                            <option value="">Select Departure Month</option>
                            @foreach($departure_month as $dm)
                                <option value="{{ $dm->id }}">{{ $dm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_picture" class="pull-right">Picture</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="file" id="i_picture" name="i_picture" class="form-control inputFile">
                    </div>
                </div>
            </div>
            
            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="i_remarks" class="pull-right">Remarks</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                    <input type="text" id="i_remarks" name="i_remarks" class="form-control" placeholder="Enter Remarks">
                    </div>
                </div>
            </div>

        </div>


    </div>

    <div class="modal-footer">
        <div class="col-md-12">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary save_intern_student">Save changes</button>
        </div>
    </div>
</form>