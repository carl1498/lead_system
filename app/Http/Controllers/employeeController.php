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
            return  '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Account" class="btn btn-info btn-xs edit_account" id="'.$data->id.'"><i class="fa fa-key"></i></button>
                <button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_employee" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                <button data-container="body" data-toggle="tooltip" data-placement="left" title="Resign" class="btn btn-warning btn-xs resign_employee" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>
                <button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_employee" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
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
}
