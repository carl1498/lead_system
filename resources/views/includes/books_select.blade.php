<label class="status_select" style="display:none;">Book Status: </label>
<select type="text" id="status_select" class="book_status_select form-control select2" style="width: 100px; display:none;">
    <option value="All">All</option>
    <option value="Available">Available</option>
    <option value="Released">Released</option>
    <option value="Pending">Pending</option>
    <option value="Lost">Lost</option>
</select>
<label class="book_type_select" style="display:none;">Book: </label>
<select type="text" id="book_type_select" class="book_status_select form-control select2" style="width: 250px; display:none;">
    <option value="All">All</option>
    @foreach ($book_type as $b)
    <option value="{{ $b->id }}">{{ $b->description }}</option>
    @endforeach
</select>
<label class="student_status_select" style="display:none;">Student Status: </label>
<select type="text" id="student_status_select" class="book_status_select form-control select2" style="width: 130px; display: none;">
    <option value="All">All</option>
    <option value="Active">Active</option>
    <option value="Final School">Final School</option>
    <option value="Back Out">Back Out</option>
    <option value="Cancelled">Cancelled</option>
</select>
<label class="program_select" style="display:none;">Program: </label>
<select type="text" id="program_select" class="book_status_select form-control select2" style="width: 150px; display:none;">
    <option value="All">All</option>
    @foreach ($program as $p)
    <option value="{{ $p->id }}">{{ $p->name }}</option>
    @endforeach
</select>
<label class="branch_select" style="display:none;">Branch: </label>
<select type="text" id="branch_select" class="book_status_select form-control select2" style="width: 100px; display:none;"
@if(onLoadBranch() != 'Makati')
disabled
@endif
>
    @if(onLoadBranch() == 'Makati')
        <option value="All">All</option>
        @foreach ($branch as $b)
        <option value="{{ $b->id }}">{{ $b->name }}</option>
        @endforeach
    @else
        @foreach ($branch as $b)
            @if($b->name == onLoadBranch())
                <option value="{{ $b->id }}">{{ $b->name }}</option>
                @break
            @endif
        @endforeach
    @endif
</select>
<label class="invoice_select" style="display:none;">Invoice: </label>
<select type="text" id="invoice_select" class="book_status_select form-control select2" style="width: 100px; display:none;"
@if(onLoadBranch() != 'Makati')
disabled
@endif
>
    <option value="All">All</option>
    @if(onLoadBranch() == 'Makati')
        @foreach ($invoice as $i)
        <option value="{{ $i->id }}">{{ $i->invoice_ref_no }}</option>
        @endforeach
    @endif
</select>
<br><br>