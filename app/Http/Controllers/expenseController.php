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
            return '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-sm edit_expense_type" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
        })
        ->make(true);
    }

    public function view_expense_particular(){
        $expense_particular = expense_particular::all();

        return Datatables::of($expense_particular)
        ->addColumn('action', function($data){
            return '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-sm edit_expense_particular" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
        })
        ->make(true);
    }

    public function view_expense(Request $request){
        $date_counter = $request->date_counter;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        $expense = expense::with('type', 'particular', 'branch', 'company_type')
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->get();

        return Datatables::of($expense)
        ->editColumn('particular', function($data){
            if(!empty($data->particular->address)){
                return $data->particular->name . ' ('.$data->particular->address.')';
            }
            return $data->particular->name;
        })
        ->addColumn('action', function($data){
            return '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-sm edit_expense" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
        })
        ->make(true);
    }

    public function save_expense_type(Request $request){
        $id = $request->t_id;
        $add_edit = $request->t_add_edit;

        $expense_type = ($add_edit == 'add') ? new expense_type : expense_type::find($id);

        $expense_type->name = $request->expense_type_name;
        $expense_type->save();
    }

    public function save_expense_particular(Request $request){
        $id = $request->p_id;
        $add_edit = $request->p_add_edit;

        $expense_particular = ($add_edit == 'add') ? new expense_particular : expense_particular::find($id);

        $expense_particular->name = $request->expense_particular_name;
        $expense_particular->tin = $request->expense_particular_tin;
        $expense_particular->address = $request->expense_particular_address;
        $expense_particular->save();
    }

    public function save_expense(Request $request){
        $id = $request->e_id;
        $add_edit = $request->e_add_edit;

        $expense = ($add_edit == 'add') ? new expense : expense::find($id);

        $expense->expense_type_id = $request->expense_type;
        $expense->expense_particular_id = $request->expense_particular;
        $expense->branch_id = $request->branch;
        $expense->lead_company_type_id = $request->company_type;
        $expense->date = $request->date;
        $expense->amount = $request->amount;
        $expense->vat = $request->vat;
        $expense->input_tax = $request->input_tax;
        $expense->check_voucher = $request->check_voucher;
        $expense->remarks = $request->remarks;
        $expense->save();
    }

    public function get_expense_type(Request $request){
        $id = $request->id;

        return expense_type::find($id);
    }

    public function get_expense_particular(Request $request){
        $id = $request->id;

        return expense_particular::find($id);
    }

    public function get_expense(Request $request){
        $id = $request->id;

        return expense::find($id);
    }

    public function expenseTypeAll(Request $request){
        $expense_type = expense_type::where('name', 'LIKE', '%'.$request->name.'%')->get();

        $array = [];
        foreach ($expense_type as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function expenseParticularAll(Request $request){
        $expense_particular = expense_particular::where('name', 'LIKE', '%'.$request->name.'%')->get();

        $array = [];
        foreach ($expense_particular as $key => $value){
            if($value['address']){
                $address = ' ('.$value['address'].')';
            }else{
                $address = '';
            }
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name'].$address
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function view_cash_disbursement(){
        $total = expense::sum('amount');
        $non_vat = expense::where('vat', 'NON-VAT')->sum('amount');
        $vat = expense::where('vat', 'VAT')->sum('amount');
        $input_tax = expense::sum('input_tax');
        $expense_type = expense_type::all();
        $expense = expense::with('particular')->groupBy('expense_particular_id')
            ->orderBy('id', 'asc')->get();
        $expense_particular_type_total = [];

        foreach($expense_type as $et){
            $et->expense_type_total = expense::where('expense_type_id', $et->id)->sum('amount');
        }
        
        foreach($expense as $e){
            $e->total_invoice = expense::where('expense_particular_id', $e->expense_particular_id)->sum('amount');
            $e->non_vat_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->where('vat', 'NON-VAT')->sum('amount');
            $e->vat_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->where('vat', 'VAT')->sum('amount');
            $e->input_tax_total = expense::where('expense_particular_id', $e->expense_particular_id)->sum('input_tax');
        }

        $x = 0;
        foreach($expense as $e){
            $y = 0;
            foreach($expense_type as $et){
                $expense_particular_type_total[$x][$y] = expense::where('expense_type_id', $et->id)
                    ->where('expense_particular_id', $e->expense_particular_id)->sum('amount');
                $y++;
            }
            $x++;
        }

        info($expense_particular_type_total);

        $output = array(
            'total' => $total,
            'non_vat' => $non_vat,
            'vat' => $vat,
            'input_tax' => $input_tax,
            'expense_type' => $expense_type,
            'expense' => $expense,
            'expense_particular_type_total' => $expense_particular_type_total,
        );

        echo json_encode($output);
    }
}