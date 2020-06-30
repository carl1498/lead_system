<div class="input-group">
    <div class="input-group-addon">
        <i class="fa fa-calendar-alt"></i>
    </div>
    <input type="text" class="form-control" id="daterange" style="width: 200px;"/>
    <input id="date_counter" type="checkbox" checked data-toggle="toggle" data-on="Date" data-off="All"
        style="visibility: hidden;">

    &nbsp;

    <input type="text" class="form-control" id="yearpicker" style="width: 100px; display: none;"/>

    &nbsp;

    <label class="company_type_select" style="display:none;">Company: </label>
    <select type="text" id="company_type_select" class="company_type_select form-control select2" style="width: 100px; display: none;">
        <option value="All">All</option>
        @foreach ($lead_company_type as $lct)
        <option value="{{ $lct->id }}">{{ $lct->name }}</option>
        @endforeach
    </select>

    &nbsp;

    <label class="branch_select" style="display:none;">Branch: </label>
    <select type="text" id="branch_select" class="branch_select form-control select2" style="width: 100px; display: none;">
        <option value="All">All</option>
        @foreach ($branch as $b)
        <option value="{{ $b->id }}">{{ $b->name }}</option>
        @endforeach
    </select>
</div>
<br>