<label class="status_select">Book Status: </label>
<select type="text" id="status_select" class="book_status_select form-control select2" style="width: 100px;">
    <option value="All">All</option>
    <option value="Available">Available</option>
    <option value="Released">Released</option>
    <option value="Pending">Pending</option>
    <option value="Lost">Lost</option>
</select>
<label class="book_type_select">Book: </label>
<select type="text" id="book_type_select" class="book_status_select form-control select2" style="width: 100px;">
    <option value="All">All</option>
    @foreach ($book_type as $b)
    <option value="{{ $b->id }}">{{ $b->name }}</option>
    @endforeach
</select>
<br><br>