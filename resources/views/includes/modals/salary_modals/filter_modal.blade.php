<div class="modal fade" id="filter_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Salary Filter</h4>
            </div>
            <form class="form-horizontal" id="filter_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="company_type_filter" class="pull-right">Company</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group required">
                                    <select type="text" id="company_type_filter" class="form-control select2" style="width: 100%;" required>
                                        <option value="All">All</option>
                                        @foreach ($lead_company_type as $lct)
                                        <option value="{{ $lct->id }}">{{ $lct->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="branch_filter" class="pull-right">Branch</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group required">
                                    <select type="text" id="branch_filter" class="form-control select2" style="width: 100%;" required>
                                        <option value="All">All</option>
                                        @foreach ($branch as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="status_filter" class="pull-right">Status</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group required">
                                    <select type="text" id="status_filter" class="form-control select2" style="width: 100%;" required>
                                        <option value="Active">Active</option>
                                        <option value="Resigned">Resigned</option>
                                        <option value="All">All</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="position_filter" class="pull-right">Position</label>
                            </div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                <div class="form-group required">
                                    <select type="text" id="position_filter" multiple="multiple" class="form-control select2" style="width: 100%;">
                                        <option value=""></option>
                                        @foreach($role as $r)
                                            <option value="{{ $r->id }}">{{ $r->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>


                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary filter_salary">SAVE CHANGES</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>