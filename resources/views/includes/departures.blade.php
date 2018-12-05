<label>Departure: </label>
<select type="text" id="year_select" class="form-control select2" style="width: 70px;">
    @foreach(departure_year() as $d)
    <option value="{{ $d->id }}">{{ $d->name }}</option>
    @endforeach
</select>
<select type="text" id="month_select" class="form-control select2" style="width: 120px;">
    @foreach(departure_month() as $m)
    <option value="{{ $m->id }}">{{ $m->name }}</option>
    @endforeach
</select>
<br><br>