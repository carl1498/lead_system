<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\expense;
use App\expense_type;
use App\expense_particular;
use App\lead_company_type;
use App\branch;
use Auth;
use Yajra\Datatables\Datatables;

class expenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $expense_type = expense_type::all();
        $expense_particular = expense_particular::all();
        $lead_company_type = lead_company_type::all();
        $branch = branch::all();
        
        return view('pages.expense', compact('expense_type', 'expense_particular', 'lead_company_type',
            'branch'));
    }

    public function view_expense_type(){
        $expense_type = expense_type::all();

        return Datatables::of($expense_type)
        ->addColumn('action', function($data){
            return '';
        })
        ->make(true);
    }

    public function save_expense_type(Request $request){
        $add_edit = $request->t_add_edit;

        if($add_edit == 'add'){
            $expense_type = new expense_type;
        }

        $expense_type->name = $request->expense_type_name;
        $expense_type->save();
    }
}
