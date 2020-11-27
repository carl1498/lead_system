<div class="modal fade" id="order_modal">
    <div class="modal-dialog" style="width: 100%; max-width: 800px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title pull-left">Order</h4>
                <div class="pull-right">
                    <input id="order_continuous" type="checkbox" checked data-toggle="toggle" data-on="Multiple" data-off="Single"
                    data-width="90" style="visibility: hidden; width: 100px !important;">&nbsp;&nbsp;&nbsp;&nbsp;
                </div>
            </div>
            <form class="form-horizontal" id="order_form">
                @csrf
                <div class="modal-body clearfix">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="add_edit" id="add_edit">

                    <div class="col-md-12">

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    <label for="client" class="pull-right">Client</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group required">
                                    <select type="text" id="client" name="client" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Client</option>
                                        @foreach($client as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="order_type" class="pull-right">Order Type</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="order_type" name="order_type" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Order Type</option>
                                        @foreach($order_type as $ot)
                                            <option value="{{ $ot->id }}">{{ $ot->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="industry" class="pull-right">Industry</label>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-7">
                                <div class="form-group required">
                                    <select type="text" id="industry" name="industry" class="form-control select2 required" style="width: 100%;" required>
                                        <option value="">Select Industry</option>
                                        <option value="Nursing Care">Nursing Care</option>
                                        <option value="Hotel">Hotel</option>
                                        <option value="Food Service">Food Service</option>
                                        <option value="Building Cleaning">Building Cleaning</option>
                                        <option value="Agriculture">Agriculture</option>
                                        <option value="Food and Drinks Manufacturing">Food and Drinks Manufacturing</option>
                                        <option value="Airport Ground Service">Airport Ground Service</option>
                                        <option value="Others">Others</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="order" class="pull-right">Order</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="number" min="0" id="order" name="order" class="form-control required" placeholder="Enter No. of Orders" required>
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 form-control-label">
                                    <label for="hires" class="pull-right">Hires</label>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                <div class="form-group">
                                    <input type="number" min="0" id="hires" name="hires" class="form-control required" placeholder="Enter No. of Hires" required>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-control-label">
                                    <label for="interview_date" class="pull-right">Interview Date</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="form-group">
                                    <input type="text" id="interview_date" name="interview_date" class="form-control datepicker" placeholder="YYYY-MM-DD">
                                </div>
                            </div>

                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-1">
                                <label>Time</label>
                            </div>
                                
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="input-group">
                                    <input type="text" name="start_time" class="start_time form-control timepicker" placeholder="Start 00:00">
                                </div>
                            </div>
                            
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
                                <div class="input-group">
                                    <input type="text" name="end_time" class="end_time form-control timepicker" placeholder="End 00:00">
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-control-label">
                                    <label for="remarks" class="pull-right">Remarks</label>
                            </div>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
                                <div class="form-group">
                                    <input type="text" id="remarks" name="remarks" class="form-control" placeholder="Enter Remarks">
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="modal-footer">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary save_order">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>