<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student;
use App\program;
use App\school;
use App\benefactor;
use App\employee;
use App\branch;
use App\course;
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
        $course = course::all();
        $departure_year = departure_year::all();
        $departure_month = departure_month::all();

        return view('pages.students', compact('program', 'school', 'benefactor', 'employee', 'branch', 'course', 'departure_year', 'departure_month'));
    }

    public function branch(Request $request){
        $current_branch = $request->current_branch;
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;

        $b = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')->get();

        $branch = $b->where('branch.name', $current_branch)->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month)->whereIn('status', ['Active', 'Final School']);

        return $this->refreshDatatable($branch);
    }

    public function refreshDatatable($branch){
        return Datatables::of($branch)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            $html = '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Final School" class="btn btn-success btn-xs final_student" id="'.$data->id.'"><i class="fa fa-user-graduate"></i></button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';

            return  $html;
        })
        ->make(true);
    }

    public function status(Request $request){
        $current_status = $request->current_status;
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;

        $s = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')->get();

        $status = $s->where('status', $current_status)->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month);

        return $this->refreshDatatableStatus($status);
    }

    public function refreshDatatableStatus($type){
        return Datatables::of($type)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            $html = '';

            if($data->status == 'Final School'){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>
                         ';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>';
            }
            else if($data->status == 'Back Out'){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>';
            }

            $html .= '
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Final School" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Final School" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            
            return $html;
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
        $student->course_id = $request->course;
        $student->departure_year_id = $request->year;
        $student->departure_month_id = $request->month;
        $student->remarks = $request->remarks;
        $student->save();
    }

    public function get_student(Request $request){
        $id = $request->id;
        $student = student::with('program', 'school', 'benefactor', 'referral', 'branch', 'course', 'departure_year', 'departure_month')->find($id);

        return $student;
    }

    public function delete_student(Request $request){
        $student = student::find($request->id);
        $student->delete();
    }

    public function final_student(Request $request){
        $student = student::find($request->id);
        $student->status = 'Final School';
        $student->save();
    }

    public function backout_student(Request $request){
        $student = student::find($request->id);
        $student->status = 'Back Out';
        $student->save();
    }

    public function continue_student(Request $request){
        $student = student::find($request->id);
        $student->status = 'Active';
        $student->save();
    }
}
