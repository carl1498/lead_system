<div class="modal fade" id="add_class_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 700px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add Class</h4>
            </div>
            <form class="form-horizontal" id="add_class_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-1"></div>

                    <div class="col-md-9">

                        <div class="row clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-3 col-xs-3 form-control-label">
                                    <label for="sensei" class="pull-right">Sensei</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <select type="text" id="sensei" name="sensei" class="form-control select2 required" style="width: 100%;" placeholder="Select Sensei" required>
                                        <option value="" disabled selected>Select Sensei</option>
                                        @foreach($sensei as $key => $s)
                                            @if($s->employment_status != 'Resigned')
                                            <option value="{{ $s->id }}">{{ $s->lname }}, {{ $s->fname }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix no-gutter">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"></div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" id="start_date" name="start_date" class="form-control datepicker required" placeholder="Start Date: YYYY-MM-DD" required>
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                            
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <div class="form-group">
                                    <input type="text" id="end_date" name="end_date" class="form-control datepicker" placeholder="End Date: YYYY-MM-DD">
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1"></div>
                        </div>

                        @include('includes.modals.student_class_modals.add_class_time')

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Enter Remarks">
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-1"></div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_class">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>