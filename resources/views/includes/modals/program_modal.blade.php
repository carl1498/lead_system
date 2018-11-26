<div class="modal fade" id="program_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Student</h4>
            </div>
            <form class="form-horizontal" id="add_program_form">
                @csrf
                <div class="modal-body clearfix">

                    <!-- LEFT COLUMN -->
                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="program_name" class="pull-right">Program Name</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group">
                                    <input type="text" id="program_name" name="program_name" class="form-control required" placeholder="Enter Program Name">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary add_program_button">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>