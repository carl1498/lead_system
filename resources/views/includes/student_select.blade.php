<label class="select_description"></label>
<select type="text" id="year_select" class="form-control select2" style="width: 70px; display:none;">
    @foreach(departure_year() as $y)
    <option value="{{ $y->id }}">{{ $y->name }}</option>
    @endforeach
    <option value="All">All</option>
</select>
<select type="text" id="month_select" class="form-control select2" style="width: 120px; display:none;">
    @foreach(departure_month() as $m)
    <option value="{{ $m->id }}">{{ $m->name }}</option>
    @endforeach
    <option value="All">All</option>
</select>&nbsp;
<label class="batch_description"></label>
<select type="text" id="batch_select" class="form-control select2" style="width: 120px; display:none;">
    <option value="All">All</option>
    @foreach($batch as $b)
    <option value="{{ $b }}">{{ $b }}</option>
    @endforeach
</select>
<br><br>