<div class="input-group">
    <button class="btn btn-success" id="filter"><i class="fas fa-sliders-h"></i> Filter</button>
    
    <form action="/excel_soa" method="POST" style="display: inline-block; margin-left: 10px;">
        @csrf
        <input type="hidden" class="class_hidden" name="class_hidden" value="All">
        <input type="hidden" class="program_hidden" name="program_hidden" value="All">
        <input type="hidden" class="batch_hidden" name="batch_hidden" value="All">
        <input type="hidden" class="counter" name="counter" value="true">
        
        <button type="submit" class="btn btn-md btn-info"><i class="fa fa-file-excel"></i> SOA</button>
    </form>
</div>
<br><br>