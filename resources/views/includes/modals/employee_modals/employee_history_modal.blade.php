<div class="modal fade" id="employee_history_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 1200px; overflow-y: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left"><span class="title_name"></span> - <span class="title_status"></span></h4>&nbsp;
                <button data-container="body" data-toggle="tooltip" data-placement="right" class="btn btn-warning btn-xs resign_rehire resign_employee"><i class="fa fa-sign-out-alt"></i></button>
                <div class="pull-right">
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Educational Background" class="btn btn-primary btn-s add_educational"><i class="fa fa-graduation-cap"></i></button>
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Employment History" class="btn btn-success btn-s add_employment_history" style="margin-right: 20px;"><i class="fa fa-briefcase"></i></button>
                </div>
            </div>
            <div class="modal-body">

                <h4 class="title_probationary"></h4>

                <table id="employment_history_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Hired Date</th>
                            <th>Until</th>
                            <th>Months</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

                <br>

                <h4>Employment History</h4>

                <table id="prev_employment_history_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="100px">Company</th>
                            <th width="150px">Address</th>
                            <th width="50px">Hired Date</th>
                            <th width="50px">Until</th>
                            <th width="40px">Months</th>
                            <th width="50px">Salary</th>
                            <th width="30px">Designation</th>
                            <th width="100px">Employment Type</th>
                            <th width="10px">Action</th>
                        </tr>
                    </thead>
                </table>

                <br>

                <h4>Educational Background</h4>

                <table id="educational_background_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="200px">School</th>
                            <th width="50px">Start</th>
                            <th width="50px">End</th>
                            <th width="100px">Course</th>
                            <th width="50px">Level</th>
                            <th width="100px">Awards</th>
                            <th width="10px">Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>