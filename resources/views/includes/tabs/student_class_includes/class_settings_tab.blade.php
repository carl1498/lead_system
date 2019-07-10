<form class="form-horizontal" id="edit_class_form">
    @csrf
    <div class="modal-body">

        <div class="col-md-1"></div>

        <div class="col-md-9">
            <input type="hidden" id="edit_class_id" name="edit_class_id">

            <div class="row clearfix">
                <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                        <label for="e_sensei" class="pull-right">Sensei</label>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="form-group required">
                        <select type="text" id="e_sensei" name="e_sensei" class="form-control select2 required" style="width: 100%;" placeholder="Select Sensei" required>
                            <option value="" disabled selected>Select Sensei</option>
                            @foreach($sensei as $key => $s)
                                <option value="{{ $s->id }}">{{ $s->lname }}, {{ $s->fname }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row clearfix no-gutter">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <input type="text" id="e_start_date" name="e_start_date" class="form-control datepicker required" placeholder="Start Date: YYYY-MM-DD" required>
                    </div>
                </div>

                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <div class="form-group">
                        <input type="text" id="e_end_date" name="e_end_date" class="form-control datepicker" placeholder="End Date: YYYY-MM-DD">
                    </div>
                </div>

                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
            </div>

            @include('includes.tabs.student_class_includes.class_settings_time')

            <div class="row clearfix">
                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                        <label for="e_remarks" class="pull-right">Remarks</label>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                    <div class="form-group required">
                    <input type="text" id="e_remarks" name="e_remarks" class="form-control" placeholder="Enter Remarks">
                    </div>
                </div>
            </div>

        </div>

        <div class="col-md-1"></div>

    </div>

    <div class="modal-footer">
        <div class="col-md-12">
            <button type="button" class="btn btn-danger pull-left delete_class">Delete Class</button>
            <button type="submit" class="btn btn-primary e_save_class">Save changes</button>
        </div>
    </div>
</form>