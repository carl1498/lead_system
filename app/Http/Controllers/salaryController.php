<?php

namespace App\Http\Controllers;
use App\Traits\SalaryTraits;

use Illuminate\Http\Request;
use App\Traits\LogsTraits;
use App\employee;
use App\emp_salary;
use App\salary_monitoring;
use App\salary_income;
use App\salary_deduction;
use App\lead_company_type;
use App\branch;
use App\role;
use Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class salaryController extends Controller
{
    use LogsTraits;
    use SalaryTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->page_access_log(Auth::user()->emp_id, 'Salary', 'Visit');

        $emp_salary = emp_salary::with('employee')->get();
        $lead_company_type = lead_company_type::all();
        $branch = branch::all();
        $role = role::all();

        return view('pages.salary', compact('emp_salary', 'lead_company_type', 'branch', 'role'));
    }

    public function view_employee_salary(Request $request){
        $company = $request->company;
        $branch = $request->branch;
        $status = $request->status;
        $role = $request->role;

        $emp_salary = emp_salary::with('employee.company_type', 'employee.branch', 'employee.role')
                    ->when($company != 'All', function($query) use($company) {
                        $query->whereHas('employee', function($query) use($company) {
                            $query->where('lead_company_type_id', $company);
                        });
                    })->when($branch != 'All', function($query) use($branch) {
                        $query->whereHas('employee', function($query) use($branch) {
                            $query->where('branch_id', $branch);
                        });
                    })->when($status != 'All', function($query) use($status) {
                        $query->whereHas('employee', function($query) use($status) {
                            $query->where('employment_status', $status);
                        });
                    })->when($role, function($query) use($role) {
                        $query->whereHas('employee', function($query) use($role) {
                            $query->whereIn('role_id', $role);
                        });
                    })->get();

        return Datatables::of($emp_salary)
        ->addColumn('name', function($data){
            return $data->employee->lname . ', ' . $data->employee->fname . ' ' . $data->employee->mname;
        })
        ->editColumn('daily', function($data){
            if($data->sal_type == 'Monthly' || $data->sal_type == 'Daily'){
                return number_format($data->daily, 2, '.', '');
            }
            return $data->daily;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_employee_salary" id="'.$data->emp_id.'"><i class="fa fa-pen"></i></button>';
            return $html;
        })
        ->make(true);
    }

    public function view_salary(Request $request){
        $company = $request->company;
        $branch = $request->branch;
        $status = $request->status;
        $role = $request->role;
        $date_counter = $request->date_counter;
        $start_date = $request->start_date;
        $end_date = $request->end_date;

        $salary = salary_monitoring::with('income', 'deduction', 'employee.branch', 'employee.company_type', 'employee.role')
                ->when($company != 'All', function($query) use($company) {
                    $query->whereHas('employee', function($query) use($company) {
                        $query->where('lead_company_type_id', $company);
                    });
                })->when($branch != 'All', function($query) use($branch) {
                    $query->whereHas('employee', function($query) use($branch) {
                        $query->where('branch_id', $branch);
                    });
                })->when($status != 'All', function($query) use($status) {
                    $query->whereHas('employee', function($query) use($status) {
                        $query->where('employment_status', $status);
                    });
                })->when($role, function($query) use($role) {
                    $query->whereHas('employee', function($query) use($role) {
                        $query->whereIn('role_id', $role);
                    });
                })->when($date_counter == 'true', function($query) use($start_date, $end_date) {
                    $query->whereBetween('pay_date', [$start_date, $end_date]);
                })->get();

        return Datatables::of($salary)
        ->addColumn('name', function($data){
            return $data->employee->lname . ', ' . $data->employee->fname . ' ' . $data->employee->mname;
        })
        ->editColumn('daily', function($data){
            if($data->sal_type != 'Yen'){
                return number_format($data->daily, 2, '.', '');
            }
        })
        ->addColumn('net', function($data){
            return $this->calculate_all('net', $data);
        })
        ->addColumn('gross', function($data){
            return $this->calculate_all('gross', $data);
        })
        ->addColumn('deduction_total', function($data){
            return $this->calculate_all('deduction', $data);
        })
        ->addColumn('wfh', function($data){
            return $this->calculate_all('wfh', $data);
        })
        ->addColumn('cutoff', function($data){
            return $data->period_from.' - '.$data->period_to;
        })
        ->addColumn('basic', function($data){
            $basic = $this->calculate_all('basic', $data); //basic_amount
            return '('.$data->income->basic.')'.' '.$basic;
        })
        ->addColumn('transpo', function($data){
            $transpo = $this->calculate_all('transpo', $data); //transpo_amount
            return ($data->income->transpo_days) ? '('.$data->income->transpo_days.')'.' '.$transpo : '';
        })
        ->addColumn('reg_ot', function($data){
            $reg_ot = $this->calculate_all('reg_ot', $data); //reg_ot_amount
            return ($data->income->reg_ot) ? '('.$data->income->reg_ot.')'.' '.$reg_ot : '';
        })
        ->addColumn('rd_ot', function($data){
            $rd_ot = $this->calculate_all('rd_ot', $data); //rd_ot_amount
            return ($data->income->rd_ot) ? '('.$data->income->rd_ot.')'.' '.$rd_ot : '';
        })
        ->addColumn('spcl', function($data){
            $spcl = $this->calculate_all('spcl', $data); //spcl_hol_amount
            return ($data->income->spcl_hol) ? '('.$data->income->spcl_hol.')'.' '.$spcl : '';
        })
        ->addColumn('leg', function($data){
            $leg = $this->calculate_all('leg', $data); //leg_hol_amount
            return ($data->income->leg_hol) ? '('.$data->income->leg_hol.')'.' '.$leg : '';
        })
        ->addColumn('spcl_ot', function($data){
            $spcl_ot = $this->calculate_all('spcl_ot', $data); //spcl_ot_amount
            return ($data->income->spcl_hol_ot) ? '('.$data->income->spcl_hol_ot.')'.' '.$spcl_ot : '';
        })
        ->addColumn('leg_ot', function($data){
            $leg_ot = $this->calculate_all('leg_ot', $data); //leg_ot_amount
            return ($data->income->leg_hol_ot) ? '('.$data->income->leg_hol_ot.')'.' '.$leg_ot : '';
        })
        ->addColumn('absence', function($data){
            $absence = $this->calculate_all('absence', $data); //leg_ot_amount
            return ($data->deduction->absence) ? '('.$data->deduction->absence.')'.' '.$absence : '';
        })
        ->addColumn('late', function($data){
            $late = $this->calculate_all('late', $data); //leg_ot_amount
            return ($data->deduction->late) ? '('.$data->deduction->late.')'.' '.$late : '';
        })
        ->addColumn('undertime', function($data){
            $undertime = $this->calculate_all('undertime', $data); //leg_ot_amount
            return ($data->deduction->undertime) ? '('.$data->deduction->undertime.')'.' '.$undertime : '';
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_salary" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_salary" id="'.$data->id.'"><i class="fa fa-trash"></i></button>';
            return $html;
        })
        ->make(true);
    }

    public function get_emp_salary($id){
        $emp_salary = emp_salary::with('employee.company_type', 'employee.branch')->where('emp_id', $id)->first();

        return $emp_salary;
    }

    public function save_emp_salary(Request $request){
        $emp_salary = emp_salary::find($request->id);
        $emp_salary->rate = $request->rate;
        $emp_salary->daily = $request->daily;
        $emp_salary->sal_type = $request->type;
        $emp_salary->cola = $request->cola;
        $emp_salary->acc_allowance = $request->accom;
        $emp_salary->transpo_allowance = $request->transpo;
        $emp_salary->sss = $request->sss;
        $emp_salary->phic = $request->phic;
        $emp_salary->hdmf = $request->hdmf;
        $emp_salary->save();
    }

    public function save_salary(Request $request){
        $s_id = $request->s_id;

        $sal_mon = ($s_id != '') ? salary_monitoring::find($s_id) : new salary_monitoring;
        $sal_mon->emp_id = $request->emp;
        $sal_mon->sal_type = $request->s_type;
        $sal_mon->rate = $request->s_rate;
        $sal_mon->daily = $request->s_daily;
        $sal_mon->period_from = $request->cutoff_from;
        $sal_mon->period_to = $request->cutoff_to;
        $sal_mon->pay_date = $request->release;
        $sal_mon->save();

        $sal_inc = ($s_id != '') ? salary_income::where('sal_mon_id', $s_id)->first() : new salary_income;
        $sal_inc->sal_mon_id = $sal_mon->id;
        $sal_inc->basic = $request->basic_days;
        $sal_inc->cola = $request->s_cola;
        $sal_inc->acc_allowance = $request->s_accom;
        $sal_inc->adjustments = $request->adjustments;
        $sal_inc->transpo_allowance = $request->s_transpo;
        $sal_inc->transpo_days = $request->transpo_days;
        $sal_inc->market_comm = $request->mktg_comm;
        $sal_inc->jap_comm = $request->jap_comm;
        $sal_inc->reg_ot = $request->reg_ot_hours;
        $sal_inc->rd_ot = $request->rd_ot_hours;
        $sal_inc->thirteenth = $request->thirteenth;
        $sal_inc->leg_hol = $request->leg_hol_days;
        $sal_inc->spcl_hol = $request->spcl_hol_days;
        $sal_inc->leg_hol_ot = $request->leg_hol_ot_hours;
        $sal_inc->spcl_hol_ot = $request->spcl_hol_ot_hours;
        $sal_inc->save();

        $sal_ded = ($s_id != '') ? salary_deduction::where('sal_mon_id', $s_id)->first() : new salary_deduction;
        $sal_ded->sal_mon_id = $sal_mon->id;
        $sal_ded->cash_advance = $request->cash_advance;
        $sal_ded->absence = $request->absence_days;
        $sal_ded->late = $request->late_days;
        $sal_ded->sss = $request->s_sss;
        $sal_ded->phic = $request->s_phic;
        $sal_ded->hdmf = $request->s_hdmf;
        $sal_ded->others = $request->others;
        $sal_ded->undertime = $request->under_days;
        $sal_ded->wfh = $request->wfh;
        $sal_ded->tax = $request->tax;
        $sal_ded->man_allocation = $request->man_allocation;
        $sal_ded->save();
    }

    public function bulk_save_salary(Request $request){
        foreach($request->b_emp as $id){
            $emp_sal = emp_salary::where('emp_id', $id)->first();
        
            $sal_mon = new salary_monitoring;
            $sal_mon->emp_id = $id;
            $sal_mon->rate = $emp_sal->rate;
            $sal_mon->daily = $emp_sal->daily;
            $sal_mon->sal_type = $emp_sal->sal_type;
            $sal_mon->period_from = $request->b_cutoff_from;
            $sal_mon->period_to = $request->b_cutoff_to;
            $sal_mon->pay_date = $request->b_release;
            $sal_mon->save();
            $sal_inc = new salary_income;
            $sal_inc->sal_mon_id = $sal_mon->id;
            $sal_inc->basic = $request->b_basic_days;
            $sal_inc->cola = $emp_sal->cola;
            if($request->allowance_counter){
                $sal_inc->acc_allowance = $emp_sal->acc_allowance;
            }
            if($emp_sal->transpo_allowance){
                $sal_inc->transpo_days = $request->b_basic_days;
            }
            $sal_inc->transpo_allowance = $emp_sal->transpo_allowance;
            $sal_inc->save();
            $sal_ded = new salary_deduction;
            $sal_ded->sal_mon_id = $sal_mon->id;
            $sal_ded->sss = $emp_sal->sss;
            $sal_ded->phic = $emp_sal->phic;
            $sal_ded->hdmf = $emp_sal->hdmf;
            $sal_ded->wfh = $request->b_wfh;
            $sal_ded->save();
        }
    }

    public function get_sal_mon($id){
        return salary_monitoring::with('employee.branch', 'employee.company_type', 'income', 'deduction')->where('id', $id)->first();
    }

    public function delete_salary(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return Redirect::to('/');
        }
        $salary = salary_monitoring::find($request->id);
        $salary->delete();
    }

    public function emp_salary_select(Request $request){
        $role = $request->role;
        $status = $request->status;

        $employee = employee::where(function ($query) use($request){
            $query->where('lname', 'like', '%'.$request->name.'%')
            ->orWhere('fname', 'like', '%'.$request->name.'%')
            ->orWhere('mname', 'like', '%'.$request->name.'%');
        })
        ->when($role != 'All', function($query) use($role){
            $query->where('role_id', $role);
        })
        ->when($status != 'All', function($query) use($status){
            $query->where('employment_status', $status);
        })->get();

        foreach($employee as $e){
            $sal_mon = salary_monitoring::where('pay_date', $request->date)->where('emp_id', $e->id)->get();
            if($sal_mon->isNotEmpty()){
                $e->pay_icon = '<i class="fa fa-check text-success"></i> ';
            }else{
                $e->pay_icon = '<i class="fa fa-minus text-danger"></i> ';
            }
        }
        $employee->toArray();

        $array = [];
        foreach ($employee as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['pay_icon'].$value['lname'].', '.$value['fname'].' '.$value['mname'],
                'title' => $value['lname'].', '.$value['fname'].' '.$value['mname']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function salary_position_select(Request $request){
        $status = $request->status;
        $name = $request->name;

        $role = role::
        with(['employee' => function($query) use($status) {
            $query->when($status != 'All', function($query) use($status){
                $query->where('employment_status', $status);
            });
        }])
        ->whereHas('employee', function($query) use($status){
            $query->when($status != 'All', function($query) use($status){
                $query->where('employment_status', $status);
            });
        })
        ->when($name != '', function($query) use($name){
            $query->where('name', 'LIKE', '%'.$name.'%');
        })->get();

        foreach($role as $r){
            $id = $r->id;

            $sal_mon = salary_monitoring::with('employee_many')
            ->where('pay_date', $request->date)
            ->whereHas('employee', function($query) use($id, $status){
                $query->where('role_id', $id)
                ->when($status != 'All', function($query) use($status){
                    $query->where('employment_status', $status);
                });
            })->distinct('emp_id')->count();
            
            if($sal_mon == count($r->employee)){
                $r->pay_icon = '<i class="fa fa-check text-success"></i> ';
            }else{
                $r->pay_icon = '<i class="fa fa-minus text-danger"></i> ';
            }
        }

        $array = [];
        $array[] = [
            'id' => 'All',
            'text' => 'All',
            'title' => 'All'
        ];
        foreach ($role as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['pay_icon'].' '.$value['name'],
                'title' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }
}
