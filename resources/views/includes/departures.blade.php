<label class="select_description">Departure: </label>
<select type="text" id="year_select" class="form-control select2" style="width: 70px;">
    @foreach(departure_year() as $y)
    <option value="{{ $y->id }}">{{ $y->name }}</option>
    @endforeach
</select>
<select type="text" id="month_select" class="form-control select2" style="width: 120px;">
    @foreach(departure_month() as $m)
    <option value="{{ $m->id }}">{{ $m->name }}</option>
    @endforeach
</select>
<br><br>