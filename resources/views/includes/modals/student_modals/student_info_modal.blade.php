<div class="modal fade" id="student_info_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 900px; overflow-y: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left"><span class="title_name"></span></h4>&nbsp;
                <div class="pull-right">
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Emergency Number" class="btn btn-danger btn-s add_emergency"><i class="fa fa-phone"></i></button>
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Employment History" class="btn btn-success btn-s add_employment_history"><i class="fa fa-briefcase"></i></button>
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Educational Background" class="btn btn-primary btn-s add_educational" style="margin-right: 20px;"><i class="fa fa-graduation-cap"></i></button>
                </div>
            </div>
            <div class="modal-body">

                <h4 class="title_probationary"></h4>

                <h4>In Case of Emergency</h4>

                <table id="student_emergency_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Relationship</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

                <br>

                <h4>Employment History</h4>

                <table id="student_employment_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th width="100px">Company Name</th>
                            <th width="30px">Position</th>
                            <th width="50px">Hired Date</th>
                            <th width="50px">Until</th>
                            <th width="10px">Action</th>
                        </tr>
                    </thead>
                </table>

                <br>

                <h4>Educational Background</h4>

                <table id="student_educational_background_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
                    <thead>
                        <tr>
                            <th width="200px">Name of School</th>
                            <th width="100px">Course</th>
                            <th width="100px">Level</th>
                            <th width="50px">Start</th>
                            <th width="50px">End</th>
                            <th width="10px">Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>