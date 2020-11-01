<div class="modal fade" id="filter_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Filter</h4>
            </div>
            <form class="form-horizontal" id="filter_form">
                @csrf
                <div class="modal-body clearfix">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="class_filter" class="pull-right">Class</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group required">
                                    <select type="text" id="class_filter" class="form-control select2" style="width: 100%;" required>
                                        <option value="All">All</option>
                                        <option value="No Class">No Class</option>
                                        @foreach($class_settings as $cs)
                                        <option value="{{ $cs->id }}">{{ $cs->sensei->fname }} | {{ $cs->start_date }} ~ 
                                            @if(!$cs->end_date)
                                            TBD
                                            @else
                                            {{$cs->end_date}}
                                            @endif
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="program_filter" class="pull-right">Program</label>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                <div class="form-group required">
                                    <select type="text" id="program_filter" class="form-control select2" style="width: 100%;" required>
                                        <option value="All">All</option>
                                        @foreach($program as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
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
                                        @foreach($branch as $b)
                                        <option value="{{ $b->id }}">{{ $b->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                <label for="departure_year_filter" class="pull-right">Departure</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group required">
                                    <select type="text" id="departure_year_filter" class="form-control select2" style="width: 100%;" required>
                                        <option value="All">All</option>
                                        @foreach($departure_year as $dy)
                                        <option value="{{ $dy->id }}">{{ $dy->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group required">
                                    <select type="text" id="departure_month_filter" class="form-control select2" style="width: 100%;" required>
                                        <option value="All">All</option>
                                        @foreach($departure_month as $dm)
                                        <option value="{{ $dm->id }}">{{ $dm->name }}</option>
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
                        <button type="submit" class="btn btn-primary filter">SAVE CHANGES</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>