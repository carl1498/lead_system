<div class="modal fade" id="student_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Student</h4>
            </div>
            <form class="form-horizontal" id="student_form">
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
                                        <option value="">Select Program</option>
                                        <option value="1">FNPL</option>
                                        <option value="2">LEAD Premium</option>
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
                                        <option value="">Select School</option>
                                        <option value="1">Nagoya Kaikei</option>
                                        <option value="2">TEC</option>
                                        <option value="3">Osaka Gakuin</option>
                                        <option value="4">Yu</option>
                                        <option value="5">J-Kokusai</option>
                                        <option value="6">Fukujukai</option>
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
                                        <option value="">Select Benefactor</option>
                                        <option value="1">Supercourt</option>
                                        <option value="2">Akikaze</option>
                                        <option value="3">Shin-ei</option>
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
                                <div class="form-group">
                                    <select type="text" id="referral" name="referral" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Referral</option>
                                        <option value="1">Benedict</option>
                                        <option value="2">Irene</option>
                                        <option value="3">Bev</option>
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
                                    <input type="text" id="sign_up" name="sign_up" class="form-control datepicker required" placeholder="MM/DD/YYYY Date of Sign Up" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="medical" class="pull-right">Medical</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="medical" name="medical" class="form-control datepicker" placeholder="MM/DD/YYYY Date of Medical">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="completion" class="pull-right">Completion</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group">
                                    <input type="text" id="completion" name="completion" class="form-control datepicker" placeholder="MM/DD/YYYY Date of Completion">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="gender" class="pull-right">Gender</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="gender" name="gender" class="form-control select2" style="width: 100%;" required>
                                        <option value="">Select Gender</option>
                                        <option value="1">Male</option>
                                        <option value="2">Female</option>
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
                                        <option value="1">Makati</option>
                                        <option value="2">Davao</option>
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
                                    <input type="text" id="course" name="course" class="form-control required" placeholder="Enter Course" required required>
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
                                        <option value="1">2019</option>
                                        <option value="2">2020</option>
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
                                        <option value="1">January</option>
                                        <option value="2">April</option>
                                        <option value="3">July</option>
                                        <option value="4">October</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>