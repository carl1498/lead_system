<form class="form-horizontal tab-pane fade in" id="ssw_student_form">
    @csrf
    <div class="modal-body">
        <input type="hidden" name="s_id" id="s_id">
        <input type="hidden" name="s_add_edit" id="s_add_edit">

        <!-- LEFT COLUMN -->
        <div class="col-md-6">

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_fname" class="pull-right">First Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="s_fname" name="s_fname" class="form-control required" placeholder="Enter First Name" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_mname" class="pull-right">Middle Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="s_mname" name="s_mname" class="form-control" placeholder="Enter Middle Name (Not M.I.)">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_lname" class="pull-right">Last Name</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="s_lname" name="s_lname" class="form-control required" placeholder="Enter Last Name" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_program" class="pull-right">Program</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="s_program" name="s_program" class="form-control select2" style="width: 100%;" required>
                        <option value="" disabled selected>Select Program</option>
                        @foreach($program as $p)
                            @if($p->name == 'SSW (Careworker)' || $p->name == 'SSW (Hospitality)')
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endif
                        @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_benefactor" class="pull-right">Benefactor</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <select type="text" id="s_benefactor" name="s_benefactor" class="form-control select2" style="width: 100%;">
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
                        <label for="s_contact" class="pull-right">Contact #</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="s_contact" name="s_contact" class="form-control required" placeholder="0912-345-6789" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_gender" class="pull-right">Gender</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="s_gender" name="s_gender" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_birthdate" class="pull-right">Birth Date</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="s_birthdate" name="s_birthdate" class="form-control datepicker required" placeholder="YYYY-MM-DD" required>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-6">

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_course" class="pull-right">Course</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="s_course" name="s_course" class="form-control select2 required" style="width: 100%;" required>
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
                        <label for="s_email" class="pull-right">Email</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="email" id="s_email" name="s_email" class="form-control required" placeholder="Enter Valid Email" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_address" class="pull-right">Address</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="s_address" name="s_address" class="form-control required" placeholder="Blg No., Street, City, Province, Zip" required>
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_referral" class="pull-right">Referral</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="s_referral" name="s_referral" class="form-control select2 required" style="width: 100%;" required>
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
                        <label for="s_sign_up" class="pull-right">Sign Up</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="text" id="s_sign_up" name="s_sign_up" class="form-control datepicker required" placeholder="YYYY-MM-DD Date of Sign Up" required>
                    </div>
                </div>
            </div>

            <!--<div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_branch" class="pull-right">Branch</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="s_branch" name="s_branch" class="form-control select2 required" style="width: 100%;" required>
                            <option value="">Select Branch</option>
                            @foreach($branch as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>-->

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_year" class="pull-right">Year</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                        <select type="text" id="s_year" name="s_year" class="form-control select2 required" style="width: 100%;" required>
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
                        <label for="s_picture" class="pull-right">Picture</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group">
                        <input type="file" id="s_picture" name="s_picture" class="form-control inputFile">
                    </div>
                </div>
            </div>

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="s_remarks" class="pull-right">Remarks</label>
                </div>
                <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                    <div class="form-group required">
                    <input type="text" id="s_remarks" name="s_remarks" class="form-control" placeholder="Enter Remarks">
                    </div>
                </div>
            </div>

        </div>

    </div>

    <div class="modal-footer">
        <div class="col-md-12">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary save_ssw_student">Save changes</button>
        </div>
    </div>
</form>