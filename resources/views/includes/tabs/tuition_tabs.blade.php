<!-- STUDENTS -- START -->

<div class="tab-pane fade in active" id="student_tab">
    
    <table id="student_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Program</th>
                <th>Branch</th>
                <th>Contact</th>
                <th>Balance</th>
                <th>Security Bond</th>
                <th>Class</th>
                <th>Status</th>
                <th>Departure</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    
</div>

<!-- STUDENTS -- END -->

<!-- PAYMENT -- START -->

<div class="tab-pane fade in" id="payment_tab">
    
    <table id="payment_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Payment Type</th>
                <th>Program</th>
                <th>Branch</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    
</div>

<!-- PAYMENT -- END -->

<!-- SEC BOND -- START -->

<div class="tab-pane fade in" id="sec_bond_tab">
    
    <table id="sec_bond_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Program</th>
                <th>Branch</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Remarks</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    
</div>

<!-- SEC BOND  -- END -->

<!-- PROGRAMS -- START -->

<div class="tab-pane fade in" id="program_tab">
    
    <table id="program_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    
</div>

<!-- PROGRAMS -- END -->

<!-- TUITION FEE BREAKDOWN -- START -->

<div class="tab-pane fade in" id="tf_breakdown_tab">
    
    <form action="/excel_tf_breakdown" method="POST">
        @csrf
        <input type="hidden" class="class_hidden" name="class_hidden" value="All">
        <input type="hidden" class="program_hidden" name="program_hidden" value="All">
        <input type="hidden" class="branch_hidden" name="branch_hidden" value="All">
        <input type="hidden" class="year_hidden" name="year_hidden" value="All">
        <input type="hidden" class="month_hidden" name="month_hidden" value="All">
        <button type="submit" class="btn btn-md btn-default">Excel</button>
    </form>
    <div style="overflow: auto; width: 100%;">
        <table id="tf_breakdown_table" class="table table-hover table-striped table-bordered">

        </table>
    </div>

</div>

<!-- TUITION FEE BREAKDOWN -- END -->

<!-- SUMMARY -- START -->

<div class="tab-pane fade in" id="summary_tab">
    
    <form action="/excel_tf_sb_summary" method="POST">
        @csrf
        <input type="hidden" class="program_hidden" name="program_hidden" value="All">
        <input type="hidden" class="branch_hidden" name="branch_hidden" value="All">
        <input type="hidden" class="year_hidden" name="year_hidden" value="All">
        <input type="hidden" class="month_hidden" name="month_hidden" value="All">
        <button type="submit" class="btn btn-md btn-default">Excel</button>
    </form>
    <div style="overflow: auto; width: 100%;">
        <table id="summary_table" class="table table-hover table-striped table-bordered">

        </table>
    </div>

</div>

<!-- SUMMARY -- END -->

<!-- SOA -- START -->

<div class="tab-pane fade in" id="soa_tab">
    
    <table id="soa_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Batch</th>
                <th>Total</th>
                <th>Amount Paid</th>
                <th>Balance</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
    
</div>

<!-- SOA -- END -->