<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\LogsTraits;
use App\expense;
use App\expense_type;
use App\expense_particular;
use App\lead_company_type;
use App\branch;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Redirect;

class expenseController extends Controller
{
    use LogsTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $this->page_access_log(Auth::user()->emp_id, 'Expense', 'Visit');

        $expense_type = expense_type::all();
        $expense_particular = expense_particular::all();
        $lead_company_type = lead_company_type::all();
        $branch = branch::all();
        
        return view('pages.expense', compact('expense_type', 'expense_particular', 'lead_company_type',
            'branch'));
    }

    public function view_expense_type(Request $request){
        $date_counter = $request->date_counter;
        $company = $request->company;
        $branch = $request->branch;
        $date_counter = $request->date_counter;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $expense_type = expense_type::all();

        return Datatables::of($expense_type)
        ->addColumn('total', function($data) use ($company, $branch, $date_counter, $start_date, $end_date){
            return expense::where('expense_type_id', $data->id)
            ->when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('amount');
        })
        ->addColumn('action', function($data){
            $html = '';
            
            if(ExpenseHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-sm edit_expense_type" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-danger btn-sm delete_expense_type" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }
            return $html;
        })
        ->make(true);
    }

    public function view_expense_particular(){
        $expense_particular = expense_particular::all();

        return Datatables::of($expense_particular)
        ->addColumn('action', function($data){
            $html = '';

            if(ExpenseHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-sm edit_expense_particular" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-sm delete_expense_particular" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }
            return $html;
        })
        ->make(true);
    }

    public function view_expense(Request $request){
        $date_counter = $request->date_counter;
        $company = $request->company;
        $branch = $request->branch;
        $date_counter = $request->date_counter;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        
        $expense = expense::with('type', 'particular', 'branch', 'company_type')
            ->when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use($branch){
                $query->where('branch_id', $branch);
            })
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
            $html = '';

            if(ExpenseHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-sm edit_expense" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-sm delete_expense" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }
            return $html;
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

    public function delete_expense_particular(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return Redirect::to('/');
        }
        $expense_particular = expense_particular::find($request->id);
        $expense_particular->delete();
    }

    public function delete_expense_type(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return Redirect::to('/');
        }
        $expense_type = expense_type::find($request->id);
        $expense_type->delete();
    }

    public function delete_expense(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return Redirect::to('/');
        }
        $expense = expense::find($request->id);
        $expense->delete();
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

    public function view_cash_disbursement(Request $request){
        $date_counter = $request->date_counter;
        $company = $request->company;
        $branch = $request->branch;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $expense_particular_type_total = [];
        $x = 0;

        $total = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('amount');

        $non_vat = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->where('vat', 'NON-VAT')->sum('amount');

        $vat = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->where('vat', 'VAT')->sum('amount');

        $input_tax = expense::
            when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })->sum('input_tax');

        $expense_type = expense_type::all();

        $expense = expense::with('particular')
            ->when($company != 'All', function($query) use($company){
                $query->where('lead_company_type_id', $company);
            })
            ->when($branch != 'All', function($query) use($branch){
                $query->where('branch_id', $branch);
            })
            ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                $query->whereBetween('date', [$start_date, $end_date]);
            })
            ->groupBy('expense_particular_id')->get();

        foreach($expense_type as $et){
            $et->expense_type_total = expense::where('expense_type_id', $et->id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('amount');
        }
        
        foreach($expense as $e){
            $e->total_invoice = expense::where('expense_particular_id', $e->expense_particular_id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('amount');

            $e->non_vat_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->where('vat', 'NON-VAT')
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('amount');

            $e->vat_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->where('vat', 'VAT')
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('amount');

            $e->input_tax_total = expense::where('expense_particular_id', $e->expense_particular_id)
                ->when($company != 'All', function($query) use($company){
                    $query->where('lead_company_type_id', $company);
                })
                ->when($branch != 'All', function($query) use($branch){
                    $query->where('branch_id', $branch);
                })
                ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                    $query->whereBetween('date', [$start_date, $end_date]);
                })->sum('input_tax');
        }

        foreach($expense as $e){
            $y = 0;
            foreach($expense_type as $et){
                $expense_particular_type_total[$x][$y] = expense::where('expense_type_id', $et->id)
                    ->where('expense_particular_id', $e->expense_particular_id)
                    ->when($company != 'All', function($query) use($company){
                        $query->where('lead_company_type_id', $company);
                    })
                    ->when($branch != 'All', function($query) use($branch){
                        $query->where('branch_id', $branch);
                    })
                    ->when($date_counter == 'true', function($query) use($start_date, $end_date){
                        $query->whereBetween('date', [$start_date, $end_date]);
                    })->sum('amount');
                $y++;
            }
            $x++;
        }

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

    public function view_fiscal_year(Request $request){
        $year = $request->year;
        $company = $request->company;
        $branch = $request->branch;
        $expense_type_total = [];
        $i = 0;
        
        $type = expense_type::all();

        $expense_per_month = array();
        $total_per_type = array();
        $total_per_month = array();
        $total_all = array();

        foreach($type as $t){
            for($x = 0; $x < 12; $x++){
                $expense_per_month[$i][$x] = expense::where('expense_type_id', $t->id)->whereMonth('date', $x+1)
                                                    ->when($company != 'All', function($query) use($company){
                                                        $query->where('lead_company_type_id', $company);
                                                    })
                                                    ->when($branch != 'All', function($query) use($branch){
                                                        $query->where('branch_id', $branch);
                                                    })->whereYear('date', $year)->sum('amount');
            }
            $total_per_type[$i] = expense::where('expense_type_id', $t->id)->whereYear('date', $year)
                                            ->when($company != 'All', function($query) use($company){
                                                $query->where('lead_company_type_id', $company);
                                            })
                                            ->when($branch != 'All', function($query) use($branch){
                                                $query->where('branch_id', $branch);
                                            })->sum('amount');
            $i++;
        }

        for($x = 0; $x < 12; $x++){
            $total_per_month[$x] = expense::whereMonth('date', $x+1)->whereYear('date', $year)
                                            ->when($company != 'All', function($query) use($company){
                                                $query->where('lead_company_type_id', $company);
                                            })
                                            ->when($branch != 'All', function($query) use($branch){
                                                $query->where('branch_id', $branch);
                                            })->sum('amount');
        }

        $total_all = expense::whereYear('date', $year)
                            ->when($company != 'All', function($query) use($company){
                                $query->where('lead_company_type_id', $company);
                            })
                            ->when($branch != 'All', function($query) use($branch){
                                $query->where('branch_id', $branch);
                            })->sum('amount');

        $output = array(
            'type' => $type,
            'expense_per_month' => $expense_per_month,
            'total_per_type' => $total_per_type,
            'total_per_month' => $total_per_month,
            'total_all' => $total_all
        );

        echo json_encode($output);
    }
}