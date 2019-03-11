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

        return view('pages.students', compact('program', 'school', 'benefactor', 
        'employee', 'branch', 'course', 'departure_year', 'departure_month'));
    }

    public function branch(Request $request){
        $current_branch = $request->current_branch;
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;

        $b = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')->get();

        $branch = $b->where('branch.name', $current_branch)->where('program.name', '<>', 'Language Only')->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month)->whereIn('status', ['Active', 'Final School']);

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

        if($current_status == 'Back Out / Cancelled'){
            $status = $s->whereIn('status', ['Back Out', 'Cancelled'])->where('program.name', '<>', 'Language Only')->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month);
        }else{
            $status = $s->where('status', $current_status)->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month);
        }
        
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
            else if($data->status == 'Back Out' || $data->status == 'Cancelled'){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>';
            }

            $html .= '
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            return $html;
        })
        ->make(true);
    }

    public function result(Request $request){
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;

        $r = student::with('program', 'school', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')->get();

        $result = $r->whereIn('status', ['Final School', 'Cancelled'])->where('program.name', '<>', 'Language Only')->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month);

        return $this->refreshDatatableResult($result);
    }

    public function refreshDatatableResult($result){
        return Datatables::of($result)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            $html = '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Approved" class="btn btn-success btn-xs approve_student" id="'.$data->id.'"><i class="fa fa-check"></i></button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Denied" class="btn btn-danger btn-xs deny_student" id="'.$data->id.'"><i class="fa fa-times"></i></button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Cancelled" class="btn btn-warning btn-xs cancel_student" id="'.$data->id.'"><i class="fa fa-ban"></i></button>
            <button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';

            return  $html;
        })
        ->make(true);
    }

    public function language(Request $request){
        $departure_year = $request->departure_year;

        $l = student::with('program', 'referral', 'branch', 'course', 'departure_year')->get();

        $language = $l->where('program.name', 'Language Only')->where('departure_year_id', $departure_year);

        return $this->refreshDatatableLanguage($language);
    }

    public function refreshDatatableLanguage($language){
        return Datatables::of($language)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })->addColumn('action', function($data){
            return 'Temporary';
        })
        ->make(true);
    }

    public function save_student(Request $request){
        $add_edit = $request->add_edit;
        $type = $request->type;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
        }
        else{
            $id = $request->id;
            $student = student::find($id);
        }

        if($type == 'Language Only'){
            $student->fname = $request->fname;
            $student->mname = $request->mname;
            $student->lname = $request->lname;
            $student->birthdate = Carbon::parse($request->birthdate);
            $student->age = $request->age;
            $student->contact = $request->contact;

            $program = program::where('name',$type)->first();
            $student->program_id = $program->id;

            $student->address = $request->address;
            $student->email = $request->email;
            $student->referral_id = $request->l_referral;
            $student->date_of_signup = Carbon::now();
            $student->date_of_medical = null;
            $student->date_of_completion = null;
            $student->gender = $request->l_gender;
            $student->branch_id = $request->l_branch;
            $student->course_id = $request->l_course;
            $student->departure_year_id = $request->l_year;
            $student->departure_month_id = 1;
            $student->remarks = $request->remarks;
            $student->save();
            return;
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
        if($request->medical){
            $student->date_of_medical = Carbon::parse($request->medical);
        }
        else{
            $student->date_of_medical = null;
        }
        if($request->completion){
            $student->date_of_completion = Carbon::parse($request->completion);
        }
        else{
            $student->date_of_completion = null;
        }
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
        $student->coe_status = 'TBA';
        $student->save();
    }

    public function approve_student(Request $request){
        $student = student::find($request->id);
        $student->coe_status = 'Approved';
        $student->save();
    }

    public function deny_student(Request $request){
        $student = student::find($request->id);
        $student->coe_status = 'Denied';
        $student->save();
    }

    public function cancel_student(Request $request){
        $student = student::find($request->id);
        $student->status = 'Cancelled';
        $student->save();
    }
}
