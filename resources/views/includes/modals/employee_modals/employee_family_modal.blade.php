<div class="modal fade" id="employee_family_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 700px; overflow-y: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left"></h4>&nbsp;
                <div class="pull-right">
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Emergency Number" class="btn btn-danger btn-s add_emergency"><i class="fa fa-phone"></i></button>
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Spouse" class="btn btn-success btn-s add_spouse"><i class="fa fa-ring"></i></button>
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Add Child" class="btn btn-primary btn-s add_child" style="margin-right: 20px;"><i class="fa fa-child"></i></button>
                </div>
            </div>
            <div class="modal-body">

                <h4 class="title_probationary">In case of Emergency</h4>

                <table id="employment_emergency_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="200px">Name</th>
                            <th width="100px">Contact #</th>
                            <th width="100px">Relationship</th>
                            <th width="50px">Action</th>
                        </tr>
                    </thead>
                </table>
                
                <br>

                <h4 class="title_probationary">Spouse</h4>

                <table id="employment_spouse_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="200px">Name</th>
                            <th width="100px">Contact #</th>
                            <th width="100px">Birthdate</th>
                            <th width="50px">Action</th>
                        </tr>
                    </thead>
                </table>
                
                <br>

                <h4 class="title_probationary">Children</h4>

                <table id="employment_child_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="200px">Name</th>
                            <th width="100px">Gender</th>
                            <th width="100px">Birthdate</th>
                            <th width="50px">Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>