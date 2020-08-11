<div class="input-group">
    <div class="input-group-addon">
        <i class="fa fa-calendar-alt"></i>
    </div>
    <input type="text" class="form-control" id="daterange" style="width: 200px;"/>
    <input id="date_counter" type="checkbox" checked data-toggle="toggle" data-on="Date" data-off="All"
        style="visibility: hidden;">

    &nbsp;

    <button class="btn btn-success" id="filter"><i class="fas fa-sliders-h"></i> Filter</button>

    <form action="/excel_salary" method="POST" style="display: inline-block; margin-left: 10px;">
        @csrf
        <input type="hidden" class="start_date_hidden" name="start_date_hidden" value="">
        <input type="hidden" class="end_date_hidden" name="end_date_hidden" value="">
        <input type="hidden" class="date_counter_hidden" name="date_counter_hidden" value="true">
        <input type="hidden" class="branch_hidden" name="branch_hidden" value="All">
        <input type="hidden" class="company_hidden" name="company_hidden" value="All">
        <input type="hidden" class="status_hidden" name="status_hidden" value="All">
        <select type="hidden" class="role_hidden" name="role_hidden[]" multiple="multiple" class="form-control" style="display:none;">
            @foreach($role as $r)
                <option value="{{ $r->id }}">{{ $r->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-md btn-primary"><i class="fa fa-file-invoice-dollar"></i> Payslip</button>
    </form>

</div>
<br>