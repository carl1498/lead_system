<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\employee;
use App\branch;
use App\role;
use App\employee_benefits;
use App\employment_history;
use App\employee_child;
use App\employee_emergency;
use App\employee_spouse;
use App\User;
use Carbon\Carbon;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use Image;

class employeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $branch = branch::all();
        $role = role::all();
        $employee = employee::all();
        
        return view('pages.employees', compact('branch', 'role'));
    }

    public function branch(Request $request){
        $current_branch = $_GET['current_branch'];
        $employee_status = $_GET['employee_status'];
        
        $branch = employee::with('role', 'branch', 'current_employment_status')->get();

        $branch = $branch->where('branch.name', $current_branch);

        if($employee_status != 'All'){
            $branch = $branch->where('branch.name', $current_branch)->where('employment_status', $employee_status);
        }

        return $this->refreshDatatable($branch);
    }

    public function all(Request $request){
        $employee_status = $request->employee_status;

        $all = employee::with('role', 'branch', 'current_employment_status')->get();

        if($employee_status != 'All'){
            $all = $all->where('employment_status', $employee_status);
        }

        return $this->refreshDatatable($all);
    }

    public function refreshDatatable($request){
        return Datatables::of($request)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-success btn-xs view_employee_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            
            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Account" class="btn btn-info btn-xs edit_account" id="'.$data->id.'"><i class="fa fa-key"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_employee" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="History" class="btn btn-warning btn-xs history_employee" id="'.$data->id.'"><i class="fa fa-history"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Family" class="btn btn-default btn-xs family_employee" id="'.$data->id.'"><i class="fa fa-users"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_employee" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            return $html;
        })
        ->editColumn('hired_date', function($data){
            if(canAccessAll()){
                $employment_history = $data->current_employment_status;

                $from = new Carbon($employment_history->hired_date);
                $to = ($employment_history) ? new Carbon($employment_history->until) : Carbon::now();
                $months = $from->diffInMonths($to);

                return $employment_history->hired_date . ' (' . $months . ')';
            }
        })
        ->make(true);
    }

    public function view_employment_history(Request $request){
        $id = $request->id;

        $employment_history = employment_history::where('emp_id', $id)->orderBy('id', 'desc')->get();

        return Datatables::of($employment_history)
        ->addColumn('months', function($data){
            $from = new Carbon($data->hired_date);
            $to = ($data) ? new Carbon($data->until) : new Carbon(Carbon::now());
            $months = $from->diffInMonths($to);

            $word = ($months>1) ? 'months' : 'month';

            return $months . ' ' . $word;
        })
        ->editColumn('until', function($data){
            if($data->until){
                return $data->until;
            }else{
                return 'Present';
            }
        })
        ->addColumn('action', function($data){
            $html = '';
            if($data->until){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_employment_history" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            }
            return $html;
        })
        ->make(true);
    }

    public function view_employee_emergency(Request $request){
        $id = $request->id;

        $employee_emergency = employee_emergency::where('emp_id', $id)->get();

        return Datatables::of($employee_emergency)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->addColumn('action', function($data){
            $html = '';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_employee_emergency" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_employee_emergency" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            return $html;
        })
        ->make(true);
    }

    public function view_employee_spouse(Request $request){
        $id = $request->id;

        $employee_spouse = employee_spouse::where('emp_id', $id)->get();

        return Datatables::of($employee_spouse)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->editColumn('birthdate', function($data){
            $birth = new Carbon($data->birthdate);
            $age = $birth->diffInYears(Carbon::now());
            return $data->birthdate . ' (' . $age . ')';
        })
        ->addColumn('action', function($data){
            $html = '';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_employee_spouse" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_employee_spouse" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            return $html;
        })
        ->make(true);
    }

    public function view_employee_child(Request $request){
        $id = $request->id;

        $employee_child = employee_child::where('emp_id', $id)->orderBy('birthdate', 'asc')->get();

        return Datatables::of($employee_child)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->editColumn('birthdate', function($data){
            $birth = new Carbon($data->birthdate);
            $age = $birth->diffInYears(Carbon::now());
            return $data->birthdate . ' (' . $age . ')';
        })
        ->addColumn('action', function($data){
            $html = '';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_employee_child" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_employee_child" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            return $html;
        })
        ->make(true);
    }

    public function save_employee(Request $request){
        if($request->hasFile('picture')){
            $fileextension = $request->picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg'){
                return false;
            }
        }

        $add_edit = $request->add_edit;

        if($add_edit == 'add'){
            $employee = new employee;
            $employee->employment_status = 'Active';
        }
        else{
            $id = $request->id;
            $employee = employee::find($id);
            $employment_history = employment_history::where('emp_id', $id)->orderBy('id', 'desc')->first();
            $employment_history->hired_date = Carbon::parse($request->hired);
            $employment_history->save();
        }
        
        $employee->fname = $request->fname;
        $employee->mname = $request->mname;
        $employee->lname = $request->lname;
        $employee->birthdate = Carbon::parse($request->birthdate);
        $employee->gender = $request->gender;
        $employee->contact_personal = $request->personal_no;
        $employee->contact_business = $request->business_no;
        $employee->email = $request->email;
        $employee->address = $request->address;
        $employee->branch_id = $request->branch;
        $employee->role_id = $request->role;
        $employee->salary = $request->salary;
        $employee->save();

        for($x = 0; $x < 4; $x++){
            if($add_edit == 'add'){
                $employee_benefits = new employee_benefits;
                $employee_benefits->emp_id = $employee->id;
            }
            else{
                $employee_benefits = employee_benefits::where('emp_id', $employee->id)
                    ->where('benefits_id', $x+1)->first();
            }
            if($x == 0){//SSS
                $employee_benefits->benefits_id = 1;
                $employee_benefits->id_number = $request->sss;
            }
            else if($x == 1){//Pag-ibig
                $employee_benefits->benefits_id = 2;
                $employee_benefits->id_number = $request->pagibig;
            }
            else if($x == 2){//Philhealth
                $employee_benefits->benefits_id = 3;
                $employee_benefits->id_number = $request->philhealth;
            }
            else if($x == 3){//TIN
                $employee_benefits->benefits_id = 4;
                $employee_benefits->id_number = $request->tin;
            }
            $employee_benefits->save();
        }

        if($request->hasFile('picture')){
            $fileextension = $request->picture->getClientOriginalExtension();
            $encryption = sha1(time().$request->picture->getClientOriginalName());
            $filename = $encryption.'.'.$fileextension;

            $request->picture->storeAs('public/img/employee', $filename);

            $employee->picture = $filename;

            $employee->save();
        }


        if($add_edit == 'add'){
            //Employee History
            $employment_history = new employment_history;
            $employment_history->emp_id = $employee->id;
            $employment_history->hired_date = Carbon::parse($request->hired);
            $employment_history->save();

            //Create Employee Account
            $name = str_replace(' ','',$employee->lname);
            $user = new User;
            $user->emp_id = $employee->id;
            $user->username = strtolower($name.$employee->id);
            $user->password = bcrypt('lead123');
            $user->save();
        }

        return $employee->id;
    }

    public function save_employment_history(Request $request){
        $id = $request->eh_id;
        $employment_history = employment_history::find($id);

        $employment_history->hired_date = $request->edit_hired_date;
        $employment_history->until = $request->edit_until;
        $employment_history->save();

        return $employment_history->emp_id;
    }

    public function save_employee_emergency(Request $request){
        $id = $request->e_id;
        $add_edit = $request->e_add_edit;

        $employee_emergency = ($add_edit  == 'add') ? new employee_emergency: employee_emergency::find($id);
        $employee_emergency->emp_id = $request->e_emp_id;
        $employee_emergency->fname = $request->e_fname;
        $employee_emergency->mname = $request->e_mname;
        $employee_emergency->lname = $request->e_lname;
        $employee_emergency->relationship = $request->e_relationship;
        $employee_emergency->contact = $request->e_contact;
        $employee_emergency->save();

        return $employee_emergency->emp_id;
    }

    public function save_employee_spouse(Request $request){
        $id = $request->s_id;
        $add_edit = $request->s_add_edit;

        $employee_spouse = ($add_edit  == 'add') ? new employee_spouse: employee_spouse::find($id);
        $employee_spouse->emp_id = $request->s_emp_id;
        $employee_spouse->fname = $request->s_fname;
        $employee_spouse->mname = $request->s_mname;
        $employee_spouse->lname = $request->s_lname;
        $employee_spouse->birthdate = $request->s_birthdate;
        $employee_spouse->contact = $request->s_contact;
        $employee_spouse->save();

        return $employee_spouse->emp_id;
    }

    public function save_employee_child(Request $request){
        $id = $request->c_id;
        $add_edit = $request->c_add_edit;

        $employee_child = ($add_edit  == 'add') ? new employee_child: employee_child::find($id);
        $employee_child->emp_id = $request->c_emp_id;
        $employee_child->fname = $request->c_fname;
        $employee_child->mname = $request->c_mname;
        $employee_child->lname = $request->c_lname;
        $employee_child->birthdate = $request->c_birthdate;
        $employee_child->gender = $request->c_gender;
        $employee_child->save();

        return $employee_child->emp_id;
    }

    public function get_employee(Request $request){
        $id = $request->id;
        $employee = employee::find($id);
        $employee_benefits = employee_benefits::where('emp_id', $id)->get();
        $employment_history = employment_history::where('emp_id', $id)->orderBy('id', 'desc')->first();
        
        $from = new Carbon($employment_history->hired_date);
        $to = ($employment_history) ? new Carbon($employment_history->until) : Carbon::now();
        $months = $from->diffInMonths($to);

        if($months >= 13){
            $employee->probationary = 'Regular';
        }else if($months >= 12){
            $employee->probationary = 'Evaluation';
        }else if($months >= 6){
            $employee->probationary = 'Evaluation | With Insurance';
        }else if($months >= 4){
            $employee->probationary = 'Mandatory Benefits';
        }else{
            $employee->probationary = 'Evaluation | Probationary/Training Period';
        }

        $output = array(
            'employee' => $employee,
            'benefits' => $employee_benefits,
            'employment_history' => $employment_history
        );

        echo json_encode($output);
    }

    public function get_employment_history(Request $request){
        $id = $request->id;

        $employment_history = employment_history::find($id);

        return $employment_history;
    }

    public function get_employee_emergency(Request $request){
        $id = $request->id;

        $employee_emergency = employee_emergency::find($id);

        return $employee_emergency;
    }

    public function get_employee_spouse(Request $request){
        $id = $request->id;

        $employee_spouse = employee_spouse::find($id);

        return $employee_spouse;
    }

    public function get_employee_child(Request $request){
        $id = $request->id;

        $employee_child = employee_child::find($id);

        return $employee_child;
    }

    public function delete_employee(Request $request){
        $employee = employee::find($request->id);
        $employee->delete();
    }

    public function delete_employee_emergency(Request $request){
        $employee_emergency = employee_emergency::find($request->id);
        $emp_id = $employee_emergency->emp_id;
        $employee_emergency->delete();

        return $emp_id;
    }

    public function delete_employee_spouse(Request $request){
        $employee_spouse = employee_spouse::find($request->id);
        $emp_id = $employee_spouse->emp_id;
        $employee_spouse->delete();

        return $emp_id;
    }

    public function delete_employee_child(Request $request){
        $employee_child = employee_child::find($request->id);
        $emp_id = $employee_child->emp_id;
        $employee_child->delete();

        return $emp_id;
    }

    public function get_account(Request $request){
        $id = $request->id;
        $account = User::with('employee')->where('emp_id', $id)->first();
        
        return $account;
    }

    public function confirm_user(Request $request){
        $id = Auth::user()->id;

        if(Hash::check($request->password, Auth::user()->password)){
            return 1;
        }
        return 0;
    }

    public function save_account(Request $request){
        $id = $request->a_id;
        $user = User::find($id);

        $user->username = $request->username;
        if($request->password){
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();
    }

    public function resign_employee(Request $request){
        $id = $request->r_id;
        
        $employee = employee::find($id);
        $employee->employment_status = 'Resigned';
        $employment_history = employment_history::where('emp_id', $id)->orderBy('id', 'desc')->first();
        $employment_history->until = $request->resignation_date;
        $employment_history->save();
        $employee->save();

        return $id;
    }

    public function rehire_employee(Request $request){
        $id = $request->rh_id;

        $employee = employee::find($id);
        $employee->employment_status = 'Active';
        $employment_history = new employment_history;
        $employment_history->emp_id = $id;
        $employment_history->hired_date = $request->rehiring_date;
        $employment_history->save();
        $employee->save();

        return $id;
    }

    public function view_profile(Request $request){
        $id = $request->id;

        $employee = employee::with('benefits', 'branch', 'role', 'current_employment_status', 'employee_emergency')->find($id);
        
        $employment_history = $employee->current_employment_status;

        $from = new Carbon($employment_history->hired_date);
        $to = ($employment_history) ? new Carbon($employment_history->until) : Carbon::now();
        $months = $from->diffInMonths($to);
        $years = $from->diffInYears($to);
        
        if($months >= 13){
            $employee->probationary = 'Regular';
        }else if($months == 12){
            $employee->probationary = 'Evaluation';
        }else if($months >= 6){
            $employee->probationary = 'Evaluation | With Insurance';
        }else if($months >= 4){
            $employee->probationary = 'Mandatory Benefits';
        }else{
            $employee->probationary = 'Evaluation | Probationary/Training Period';
        }

        $employee->months = $months;

        $leaves = 0;

        if($years == 1){
            $leaves = 3;
        }
        else if($years > 1){
            $leaves = 3 + $years;
        }
        
        $employee->leaves = ($leaves == 0) ? '': '(VL:'.$leaves.') (SL:2)';

        $birth = new Carbon($employee->birthdate);
        $employee->age = $birth->diffInYears(Carbon::now());

        if(!canAccessAll()){
            $employee->current_employment_status->hired_date = null;
            $employee->current_employment_status->until = '-';
            $employee->months = null;
            foreach($employee->benefits as $emp){
                $emp->id_number = null;
            }
        }
        return $employee;
    }
}
