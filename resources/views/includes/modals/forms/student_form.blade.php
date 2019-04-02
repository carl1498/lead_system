<form class="form-horizontal tab-pane fade in" id="student_form">
    @csrf
    <div class="modal-body">
        <input type="hidden" name="id" id="id">
        <input type="hidden" name="add_edit" id="add_edit">

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
                        <input type="text" id="birthdate" name="birthdate" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="age" class="pull-right">Age</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="number" id="age" name="age" class="form-control required" placeholder="Enter Age" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="contact" class="pull-right">Contact #</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="contact" name="contact" class="form-control required" placeholder="0912-345-6789" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="program" class="pull-right">Program</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <select type="text" id="program" name="program" class="form-control select2" style="width: 100%;">
                            <option value="" disabled selected>Select Program</option>
                            @foreach($program as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="school" class="pull-right">School</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <select type="text" id="school" name="school" class="form-control select2" style="width: 100%;">
                            <option value="" disabled selected>Select School</option>
                            @foreach($school as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="benefactor" class="pull-right">Benefactor</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <select type="text" id="benefactor" name="benefactor" class="form-control select2" style="width: 100%;">
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
                        <label for="address" class="pull-right">Address</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="address" name="address" class="form-control required" placeholder="Enter Address" required>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="email" class="pull-right">Email</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="email" id="email" name="email" class="form-control required" placeholder="Enter Valid Email" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="referral" class="pull-right">Referral</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="referral" name="referral" class="form-control select2 required" style="width: 100%;" required>
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
                        <label for="sign_up" class="pull-right">Sign Up</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="sign_up" name="sign_up" class="form-control datepicker required" placeholder="YYYY-MM-DD Date of Sign Up" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="medical" class="pull-right">Medical</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="medical" name="medical" class="form-control datepicker" placeholder="YYYY-MM-DD Date of Medical">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="completion" class="pull-right">Completion</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="completion" name="completion" class="form-control datepicker" placeholder="YYYY-MM-DD Date of Completion">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="gender" class="pull-right">Gender</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
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
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="branch" class="pull-right">Branch</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="branch" name="branch" class="form-control select2 required" style="width: 100%;" required>
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
                        <label for="course" class="pull-right">Course</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="course" name="course" class="form-control select2 required" style="width: 100%;" required>
                            <option value="" disabled selected>Select Course</option>
                            @foreach($course as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="year" class="pull-right">Year</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="year" name="year" class="form-control select2 required" style="width: 100%;" required>
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
                        <label for="month" class="pull-right">Month</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="month" name="month" class="form-control select2" style="width: 100%;">
                            <option value="">Select Departure Month</option>
                            @foreach($departure_month as $dm)
                                <option value="{{ $dm->id }}">{{ $dm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="remarks" class="pull-right">Remarks</label>
                </div>
                <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                    <div class="form-group required">
                    <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Enter Remarks">
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