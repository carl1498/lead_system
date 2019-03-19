<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use App\branch;
use App\role;
use App\employee_benefits;
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

        return view('pages.employees', compact('branch', 'role'));
    }

    public function branch(Request $request){
        $current_branch = $_GET['current_branch'];
        $b = employee::with('role', 'branch')->get();

        $branch = $b->where('branch.name', $current_branch);

        return $this->refreshDatatable($branch);
    }

    public function refreshDatatable($branch){
        return Datatables::of($branch)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-success btn-xs view_employee_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            
            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Account" class="btn btn-info btn-xs edit_account" id="'.$data->id.'"><i class="fa fa-key"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_employee" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                if($data->employment_status == 'Active'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Resign" class="btn btn-warning btn-xs resign_employee" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
                }
                else if($data->employment_status == 'Resigned'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Rehire" class="btn btn-warning btn-xs rehire_employee" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>&nbsp;';
                }
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_employee" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            return $html;
        })
        ->editColumn('hired_date', function($data){
            if(canAccessAll()){
                return $data->hired_date;
            }
        })
        ->make(true);
    }

    public function save_employee(Request $request){
        $add_edit = $request->add_edit;

        if($add_edit == 'add'){
            $employee = new employee;
            $employee->employment_status = 'Active';
        }
        else{
            $id = $request->id;
            $employee = employee::find($id);
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
        $employee->hired_date = Carbon::parse($request->hired);
        $employee->save();

        $employee_id = employee::orderBy('id', 'DESC')->first();

        for($x = 0; $x < 4; $x++){
            if($add_edit == 'add'){
                $employee_benefits = new employee_benefits;
                $employee_benefits->emp_id = $employee_id->id;
            }
            else{
                $employee_benefits = employee_benefits::where('emp_id', $employee_id->id)
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

        //Create Employee Account
        if($add_edit == 'add'){
            $user = new User;
            $user->emp_id = $employee->id;
            $user->email = $employee->email;
            $user->password = bcrypt('lead123');
            $user->save();
        }
    }

    public function get_employee(){
        $id = $_GET['id'];
        $employee = employee::find($id);
        $employee_benefits = employee_benefits::where('emp_id', $id)->get();

        $output = array(
            'employee' => $employee,
            'benefits' => $employee_benefits
        );

        echo json_encode($output);
    }

    public function delete_employee(Request $request){
        $employee = employee::find($request->id);
        $employee->delete();
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
        info($user);

        $user->email = $request->a_email;
        if($request->password){
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();
    }

    public function resign_employee(Request $request){
        $id = $request->r_id;
        
        $employee = employee::find($id);
        $employee->employment_status = 'Resigned';
        $employee->resignation_date = $request->resignation_date;
        $employee->save();
    }

    public function rehire_employee(Request $request){
        $id = $request->rh_id;

        $employee = employee::find($id);
        $employee->employment_status = 'Active';
        $employee->resignation_date = null;
        $employee->hired_date = $request->rehiring_date;
        $employee->save();
    }

    public function view_profile(Request $request){
        $id = $request->id;

        $employee = employee::with('benefits', 'branch', 'role')->find($id);
        if(!canAccessAll()){
            $employee->hired_date = null;
            $employee->resignation_date = null;
            foreach($employee->benefits as $emp){
                $emp->id_number = null;
            }
        }
        return $employee;
    }
}
