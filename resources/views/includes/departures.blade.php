<label class="select_description"></label>
<select type="text" id="year_select" class="form-control select2" style="width: 70px; display:none;">
    @foreach(departure_year() as $y)
    <option value="{{ $y->id }}">{{ $y->name }}</option>
    @endforeach
</select>
<select type="text" id="month_select" class="form-control select2" style="width: 120px; display:none;">
    @foreach(departure_month() as $m)
    <option value="{{ $m->id }}">{{ $m->name }}</option>
    @endforeach
</select>
<br><br>