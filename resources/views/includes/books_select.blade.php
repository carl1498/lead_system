<label class="status_select">Book Status: </label>
<select type="text" id="status_select" class="book_status_select form-control select2" style="width: 100px;">
    <option value="All">All</option>
    <option value="Available">Available</option>
    <option value="Released">Released</option>
    <option value="Pending">Pending</option>
    <option value="Lost">Lost</option>
</select>
<label class="book_type_select">Book: </label>
<select type="text" id="book_type_select" class="book_status_select form-control select2" style="width: 200px;">
    <option value="All">All</option>
    @foreach ($book_type as $b)
    <option value="{{ $b->id }}">{{ $b->description }}</option>
    @endforeach
</select>
<label class="student_status_select">Student Status: </label>
<select type="text" id="student_status_select" class="book_status_select form-control select2" style="width: 130px; display: inline-block;">
    <option value="All">All</option>
    <option value="Active">Active</option>
    <option value="Final School">Final School</option>
    <option value="Back Out">Back Out</option>
    <option value="Cancelled">Cancelled</option>
</select>
<label class="program_select">Program: </label>
<select type="text" id="program_select" class="book_status_select form-control select2" style="width: 150px;">
    <option value="All">All</option>
    @foreach ($program as $p)
    <option value="{{ $p->id }}">{{ $p->name }}</option>
    @endforeach
</select>
<br><br>