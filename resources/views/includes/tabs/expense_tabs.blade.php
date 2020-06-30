<!-- EXPENSE -- START -->

<div class="tab-pane fade in active" id="expense_tab">
    
    <table id="expense_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Type</th>
                <th>Particular</th>
                <th>Branch</th>
                <th>Company</th>
                <th>Date</th>
                <th>Amount</th>
                <th>VAT</th>
                <th>Input Tax</th>
                <th>Check Voucher</th>
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
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>

</div>

<!-- EXPENSE -- END -->

<!-- TYPE -- START -->

<div class="tab-pane fade in" id="type_tab">
    
    <table id="type_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>

</div>

<!-- TYPE -- END -->

<!-- PARTICULAR -- START -->

<div class="tab-pane fade in" id="particular_tab">
    
    <table id="particular_table" class="table table-hover table-striped table-bordered responsive nowrap" width="100%">
        <thead>
            <tr>
                <th>Name</th>
                <th>TIN</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>

</div>

<!-- PARTICULAR -- END -->

<!-- CASH DISBURSEMENT -- START -->

<div class="tab-pane fade in" id="cash_disbursement_tab">
    
    <form action="/excel_expense" method="POST">
        @csrf
        <input type="hidden" class="start_date_hidden" name="start_date_hidden" value="All">
        <input type="hidden" class="end_date_hidden" name="end_date_hidden" value="All">
        <input type="hidden" class="date_counter_hidden" name="date_counter_hidden" value="true">
        <input type="hidden" class="branch_hidden" name="branch_hidden" value="All">
        <input type="hidden" class="company_hidden" name="company_hidden" value="All">
        <button type="submit" class="btn btn-md btn-default">Excel</button>
    </form>
    <div style="overflow: auto; width: 100%;">
        <table id="cash_disbursement_table" class="table table-hover table-striped table-bordered">

        </table>
    </div>

</div>

<!-- CASH DISBURSEMENT -- END -->

<!-- CASH DISBURSEMENT -- START -->

<div class="tab-pane fade in" id="fiscal_year_tab">
    
    <form action="/excel_fiscal_year" method="POST">
        @csrf
        <input type="hidden" class="year_hidden" name="year_hidden">
        <input type="hidden" class="branch_hidden" name="branch_hidden" value="All">
        <input type="hidden" class="company_hidden" name="company_hidden" value="All">
        <button type="submit" class="btn btn-md btn-default">Excel</button>
    </form>
    <div style="overflow: auto; width: 100%;">
        <table id="fiscal_year_table" class="table table-hover table-striped table-bordered">

        </table>
    </div>

</div>

<!-- CASH DISBURSEMENT -- END -->