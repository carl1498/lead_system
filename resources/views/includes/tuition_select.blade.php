<label class="class_select" style="display:none;">Class: </label>
<select type="text" id="class_select" class="form-control select2" style="width: 300px; display:none;">
    <option value="All">All</option>
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

<label class="program_select" style="display:none;">Program: </label>
<select type="text" id="program_select" class="form-control select2" style="width: 170px; display:none;">
    <option value="All">All</option>
    @foreach($program as $p)
    <option value="{{ $p->id }}">{{ $p->name }}</option>
    @endforeach
</select>

<label class="departure_select" style="display:none;">Departure: </label>
<select type="text" id="departure_year_select" class="form-control select2" style="width: 70px; display:none;">
    <option value="All">All</option>
    @foreach($departure_year as $dy)
    <option value="{{ $dy->id }}">{{ $dy->name }}</option>
    @endforeach
</select>
<select type="text" id="departure_month_select" class="form-control select2" style="width: 120px; display:none;">
    <option value="All">All</option>
    @foreach($departure_month as $dm)
    <option value="{{ $dm->id }}">{{ $dm->name }}</option>
    @endforeach
</select>
<br><br>