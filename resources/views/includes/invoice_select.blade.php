<label class="type_select" style="display:none;">Type: </label>
<select type="text" id="type_select" class="form-control select2" style="width: 100px; display:none;">
    <option value="Quantity">Quantity</option>
    <option value="Pending">Pending</option>
</select>

<label class="book_type_select" style="display:none;">Book: </label>
<select type="text" id="book_type_select" class="form-control select2" style="width: 280px; display:none;">
    <option value="All">All</option>
    @foreach ($book_type as $b)
    <option value="{{ $b->id }}">{{ $b->description }}</option>
    @endforeach
</select>
<label class="invoice_select" style="display:none;">Invoice: </label>
<select type="text" id="invoice_select" class="form-control select2" style="width: 100px; display:none;">
    <option value="All">All</option>
    @foreach ($invoice as $i)
    <option value="{{ $i->id }}">{{ $i->invoice_ref_no }}</option>
    @endforeach
</select>
<br><br>