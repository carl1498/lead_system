<div class="modal fade" id="student_tuition_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 400px; overflow-y: auto;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left"></h4>&nbsp;
                <div class="pull-right">
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit Initial Balance" class="btn btn-info btn-s add_emergency" style="margin-right: 20px;"><i class="fa fa-pen"></i></button>
                </div>
            </div>
            <div class="modal-body">

                <h5 class="title_class">Class: <span class="current_class"></span></h5>

                <br>

                <h4 class="title_probationary">Tuition Fee - <span class="tf_balance"></span></h4>

                <table id="tuition_fee_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Date of Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
                
                <br>

                <h4 class="title_probationary">Security Bond - <span class="tf_sb_total"></span></h4>

                <table id="sec_bond_table" class="table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>Amount</th>
                            <th>Date of Payment</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>
    </div>
</div>