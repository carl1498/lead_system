<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student;
use App\program;
use App\school;
use App\benefactor;
use App\employee;
use App\branch;
use App\departure_year;
use App\departure_month;
use Carbon\Carbon;
use Auth;
use Yajra\Datatables\Datatables;

class studentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $program = program::all();
        $school = school::all();
        $benefactor = benefactor::all();
        $employee = employee::all();
        $branch = branch::all();
        $departure_year = departure_year::all();
        $departure_month = departure_month::all();

        return view('pages.students', compact('program', 'school', 'benefactor', 'employee', 'branch', 'departure_year', 'departure_month'));
    }

    public function makati(){
        $m = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $makati = $m->where('branch.name', 'Makati');

        return $this->refreshDatatable($makati);
    }

    public function naga(){
        $n = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $naga = $n->where('branch.name', 'Naga');

        return $this->refreshDatatable($naga);
    }
    
    public function cebu(){
        $c = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $cebu = $c->where('branch.name', 'Cebu');

        return $this->refreshDatatable($cebu);
    }
    
    public function davao(){
        $d = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $davao = $d->where('branch.name', 'Davao');

        return $this->refreshDatatable($davao);
    }

    public function refreshDatatable($branch){
        return Datatables::of($branch)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            return  '<button class="btn btn-warning btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                    <button class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
        })
        ->make(true);
    }

    public function save_student(Request $request){
        $add_edit = $request->add_edit;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
        }
        else{
            $id = $request->id;
            $student = student::find($id);
        }

        $student->fname = $request->fname;
        $student->mname = $request->mname;
        $student->lname = $request->lname;
        $student->birthdate = Carbon::parse($request->birthdate);
        $student->age = $request->age;
        $student->contact = $request->contact;
        $student->program_id = $request->program;
        $student->school_id = $request->school;
        $student->benefactor_id = $request->benefactor;
        $student->address = $request->address;
        $student->email = $request->email;
        $student->referral_id = $request->referral;
        $student->date_of_signup = Carbon::parse($request->sign_up);
        $student->date_of_medical = Carbon::parse($request->medical);
        $student->date_of_completion = Carbon::parse($request->completion);
        $student->gender = $request->gender;
        $student->branch_id = $request->branch;
        $student->course = $request->course;
        $student->departure_year_id = $request->year;
        $student->departure_month_id = $request->month;
        $student->remarks = $request->remarks;
        $student->save();
    }

    public function get_student(Request $request){
        $id = $request->id;
        $student = student::with('program', 'school', 'benefactor', 'employee', 'branch', 'departure_year', 'departure_month')->find($id);

        return $employee;
    }

    public function delete_employee(Request $request){
        $employee = employee::find($request->id);
        $employee->delete();
    }
}
