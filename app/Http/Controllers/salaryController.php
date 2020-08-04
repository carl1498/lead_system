<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use App\emp_salary;
use App\salary_monitoring;
use App\salary_income;
use App\salary_deduction;
use Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;

class salaryController extends Controller
{

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
        $emp_salary = emp_salary::with('employee')->get();

        return view('pages.salary', compact('emp_salary'));
    }

    public function view_employee_salary(){
        $emp_salary = emp_salary::with('employee.company_type', 'employee.branch')->get();

        return Datatables::of($emp_salary)
        ->addColumn('name', function($data){
            return $data->employee->lname . ', ' . $data->employee->fname . ' ' . $data->employee->mname;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_employee_salary" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            return $html;
        })
        ->make(true);
    }

    public function view_salary(){
        $salary = salary_monitoring::with('income', 'deduction', 'employee.branch', 'employee.company_type')->get();

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
            return ($data->deduction->wfh) 
            ? ($data->deduction->wfh == floor($data->deduction->wfh) 
                ? floor($data->deduction->wfh).'%' : $data->deduction->wfh.'%') 
            : '';
        })
        ->addColumn('cutoff', function($data){
            return $data->period_from.' - '.$data->period_to;
        })
        ->addColumn('basic', function($data){
            $basic = $this->calculate_all('basic', $data); //basic_amount
            return '('.$data->income->basic.')'.' '.$basic;
        })
        ->addColumn('reg_ot', function($data){
            $reg_ot = $this->calculate_all('reg_ot', $data); //reg_ot_amount
            return ($data->income->reg_ot) ? '('.$data->income->reg_ot.')'.' '.$reg_ot : '';
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

    public function calculate_all($type, $d){
        $rate = $d->rate;
        $sal_type = $d->sal_type;
        $daily = $d->daily;

        $income_arr = ['basic', 'gross', 'reg_ot', 'spcl', 'leg',
                        'spcl_ot', 'leg_ot', 'net',];

        $deduction_arr = ['deduction', 'absence', 'late', 'undertime',
                            'net'];

        if(in_array($type, $income_arr)){
            $inc = $d->income;
            $gross = 0;

            if($d->sal_type == 'Monthly'){
                $gross += $rate / 2;
            }
            else if($d->sal_type == 'Daily'){
                $basic_days = $inc->basic;
                $gross += $daily * $basic_days; 
            }

            if($type == 'basic'){
                return $gross;
            }
            
            $gross += $inc->cola;
            $gross += $inc->acc_allowance;
            $gross += $inc->adjustments;
            $gross += $inc->transpo_allowance;
            $gross += $inc->market_comm;
            $gross += $inc->jap_comm;
            $gross += $inc->thirteenth;
            
            $ot_hours = [$inc->reg_ot, $inc->leg_hol, 
            $inc->spcl_hol, $inc->leg_hol_ot, $inc->spcl_hol_ot];

            $ot = ['reg', 'leg', 'spcl', 'leg_ot', 'spcl_ot'];

            for($x = 0; $x < count($ot); $x++){
                $amount = ($daily / 8) * $ot_hours[$x];

                if($ot[$x] == 'reg'){
                    $amount = number_format($amount * 1.25, 2, '.', '');
                    if($type == 'reg_ot') return $amount;
                }
                else if($ot[$x] == 'spcl' || $ot[$x] == 'spcl_ot'){
                    $amount = number_format($amount + ($amount *0.3), 2, '.', '');
                    if($type == 'spcl') return $amount;
                    if($type == 'spcl_ot') return $amount;
                }
                else if($ot[$x] == 'leg' || $ot[$x] == 'leg_ot'){
                    $amount = number_format($amount * 2, 2, '.', '');
                    if($type == 'leg') return $amount;
                    if($type == 'leg_ot') return $amount;
                }

                $gross += $amount;
            }

            if($type == 'gross'){
                return number_format($gross, 2, '.', '');
            }
        }
        if(in_array($type, $deduction_arr)){
            $ded = $d->deduction;
            $deduction = 0;

            $deduction += $ded->cash_advance;
            $deduction += $ded->sss;
            $deduction += $ded->phic;
            $deduction += $ded->hdmf;
            $deduction += $ded->others;
            $deduction += $ded->tax;
            $deduction += $ded->man_allocation;

            $absence = number_format($daily * $ded->absence, 2, '.', '');
            if($type == 'absence') return $absence;
            $late = number_format(($daily / 8) * $ded->late, 2, '.', '');
            if($type == 'late') return $late;
            $undertime = number_format(($daily / 8) * $ded->undertime, 2, '.', '');
            if($type == 'undertime') return $undertime;
            
            $deduction += $absence + $late + $undertime;

            if($type == 'deduction'){
                return number_format($deduction, 2, '.', '');
            }
        }
        if($type == 'net'){
            $net = $gross - $deduction;
            if($d->deduction->wfh && $d->deduction->wfh > 0){
                $net = $net * ($d->deduction->wfh / 100);
            }

            return number_format($net, 2, '.', '');
        }
    }

    public function get_emp_salary(Request $request, $id){
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
        $sal_inc->market_comm = $request->mktg_comm;
        $sal_inc->jap_comm = $request->jap_comm;
        $sal_inc->reg_ot = $request->reg_ot_hours;
        $sal_inc->thirteenth = $request->thirteenth;
        $sal_inc->leg_hol = $request->leg_hol_hours;
        $sal_inc->spcl_hol = $request->spcl_hol_hours;
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
        $employee = employee::all();
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
}
