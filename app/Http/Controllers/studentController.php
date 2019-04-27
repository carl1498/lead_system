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
use App\student_edit_history;
use App\student_delete_history;
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
        $employee = employee::withTrashed()->get();
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
        'branch', 'course', 'departure_year', 'departure_month')->orderBy('school_id')->get();

        $branch = $b->where('branch.name', $current_branch)->where('program.name', '<>', 'Language Only')->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month)->whereIn('status', ['Active', 'Final School']);

        return $this->refreshDatatable($branch);
    }

    public function refreshDatatable($branch){
        return Datatables::of($branch)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';

            if(canAccessAll()){
                if($data->status == 'Final School'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>&nbsp;';
                }
                else if($data->status == 'Active'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Final School" class="btn btn-success btn-xs final_student" id="'.$data->id.'"><i class="fa fa-user-graduate"></i></button>&nbsp;';
                }
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
            }
            
            if(canAccessAll() || canEditStudentList()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            }

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';    
            }
            return  $html;
        })
        ->make(true);
    }

    public function status(Request $request){
        $current_status = $request->current_status;
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;

        $s = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')->orderBy('school_id')->get();

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

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';

            if(canAccessAll()){
                if($data->status == 'Final School'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>&nbsp;';
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
                }
                else if($data->status == 'Back Out' || $data->status == 'Cancelled'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>&nbsp;';
                }
            }

            if(canAccessAll() || canEditStudentList()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';    
            }
            
            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            return $html;
        })
        ->make(true);
    }

    public function result(Request $request){
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;

        $r = student::with('program', 'school', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')->orderBy('school_id')->get();

        $result = $r->whereIn('status', ['Final School', 'Cancelled'])->where('program.name', '<>', 'Language Only')->where('departure_year_id', $departure_year)->where('departure_month_id', $departure_month);

        return $this->refreshDatatableResult($result);
    }

    public function refreshDatatableResult($result){
        return Datatables::of($result)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Approved" class="btn btn-success btn-xs approve_student" id="'.$data->id.'"><i class="fa fa-check"></i></button>
                <button data-container="body" data-toggle="tooltip" data-placement="left" title="Denied" class="btn btn-danger btn-xs deny_student" id="'.$data->id.'"><i class="fa fa-times"></i></button>
                <button data-container="body" data-toggle="tooltip" data-placement="left" title="Cancelled" class="btn btn-warning btn-xs cancel_student" id="'.$data->id.'"><i class="fa fa-ban"></i></button>
                <button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            }

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
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';

            if(canAccessAll() || canEditLanguageStudent()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_language_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            return  $html;
        })
        ->make(true);
    }

    public function ssv(Request $request){
        $departure_year = $request->departure_year;
        $current_ssv = $request->current_ssv;

        $s = student::with('program', 'referral', 'course', 'departure_year')
            ->whereHas('program', function($query) use ($request) {
                $query->where('name', 'SSV (Careworker)')->orWhere('name', 'SSV (Hospitality)');
            })->get();

        if($current_ssv == 'SSV'){
            $s = $s->where('status', 'Active');
        }
        else if($current_ssv = 'Backout'){
            $s = $s->where('status', 'Back Out');
        }
        
        $ssv = $s->where('departure_year_id', $departure_year);

        return $this->refreshDatatableSSV($ssv);
    }

    public function refreshDatatableSSV($ssv){
        return Datatables::of($ssv)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';

            if(canAccessAll()){
                if($data->status == 'Active'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
                }
                else if($data->status == 'Back Out'){
                    
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>&nbsp;';
                }
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_ssv_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            return  $html;
        })
        ->make(true);
    }

    public function save_student(Request $request){
        $add_edit = $request->add_edit;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
        }
        else{
            $id = $request->id;
            $student = student::find($id);
            $edited_by = Auth::user()->emp_id;
        }

        // EDIT HISTORY -- START

        if(isset($edited_by)){
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date', 'Age', 'Contact #', 
                'Program', 'School', 'Benefactor', 'Address', 'Email', 'Referred By', 'Sign Up Date', 
                'Medical Date', 'Completion Date', 'Gender', 'Branch', 'Course', 'Year', 'Month', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname, $student->birthdate,
                $student->age, $student->contact, $student->program_id, $student->school_id,
                $student->benefactor_id, $student->address, $student->email, $student->referral_id,
                $student->date_of_signup, $student->date_of_medical, $student->date_of_completion,
                $student->gender, $student->branch_id, $student->course_id, $student->departure_year_id,
                $student->departure_month_id, $student->remarks];

            $request_fields = [$request->fname, $request->mname, $request->lname, $request->birthdate,
                $request->age, $request->contact, $request->program, $request->school,
                $request->benefactor, $request->address, $request->email, $request->referral,
                $request->sign_up, $request->medical, $request->completion, $request->gender, 
                $request->branch, $request->course, $request->year, $request->month, $request->remarks];

            for($x = 0; $x<count($edit_fields); $x++){
                if($student_fields[$x] != $request_fields[$x]){
                    $edit_history = new student_edit_history;
                    $edit_history->stud_id = $student->id;
                    $edit_history->field = $edit_fields[$x];
                    if($edit_fields[$x] == 'Program' || $edit_fields[$x] == 'School' || $edit_fields[$x] == 'Benefactor' ||
                        $edit_fields[$x] == 'Referred By' || $edit_fields[$x] == 'Branch' || $edit_fields[$x] == 'Course' || 
                        $edit_fields[$x] == 'Year' || $edit_fields[$x] == 'Month'){
                        if($student_fields[$x] == null){
                            $prev = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Program'){
                                $prev = program::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'School'){
                                $prev = school::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Benefactor'){
                                $prev = benefactor::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Referred By'){
                                $prev = employee::where('id', $student_fields[$x])->pluck('fname');
                            }
                            else if($edit_fields[$x] == 'Branch'){
                                $prev = branch::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $prev = course::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Year'){
                                $prev = departure_year::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Month'){
                                $prev = departure_month::where('id', $student_fields[$x])->pluck('name');
                            }
                            $prev = $prev[0];
                        }

                        $edit_history->previous = $prev;

                        if($request_fields[$x] == null){
                            $new = 'N/A';
                        }
                        else{
                            if($edit_fields[$x] == 'Program'){
                                $new = program::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'School'){
                                $new = school::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Benefactor'){
                                $new = benefactor::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Referred By'){
                                $new = employee::where('id', $request_fields[$x])->pluck('fname');
                            }
                            else if($edit_fields[$x] == 'Branch'){
                                $new = branch::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $new = course::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Year'){
                                $new = departure_year::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Month'){
                                $new = departure_month::where('id', $request_fields[$x])->pluck('name');
                            }
                            $new = $new[0];
                        }

                        $edit_history->new = $new;
                    }
                    else{
                        $edit_history->previous = (isset($student_fields[$x])) ? (string) $student_fields[$x] : 'N/A';
                        $edit_history->new = (isset($request_fields[$x])) ? (string) $request_fields[$x] : 'N/A';
                    }
                    $edit_history->edited_by = $edited_by;
                    $edit_history->save();
                }
            }
        }

        // EDIT HISTORY -- END

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
        $student->date_of_medical = $request->medical ? Carbon::parse($request->medical) : null;
        $student->date_of_completion = $request->completion ? Carbon::parse($request->completion) : null;
        $student->gender = $request->gender;
        $student->branch_id = $request->branch;
        $student->course_id = $request->course;
        $student->departure_year_id = $request->year;
        $student->departure_month_id = $request->month;
        $student->remarks = $request->remarks;
        $student->save();
    }

    public function save_language_student(Request $request){
        $add_edit = $request->l_add_edit;
        $type = $request->l_student_type;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
        }
        else{
            $id = $request->l_id;
            $student = student::find($id);
            $edited_by = Auth::user()->emp_id;
        }
        
        if(isset($edited_by)){
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date',
                'Age', 'Contact #', 'Address', 'Email', 'Referred By', 'Gender', 
                'Branch', 'Course', 'Year', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname,
                $student->birthdate, $student->age, $student->contact, $student->address,
                $student->email, $student->referral_id,
                $student->gender, $student->branch_id, $student->course_id,
                $student->departure_year_id, $student->remarks];

            $request_fields = [$request->l_fname, $request->l_mname, $request->l_lname,
                $request->l_birthdate, $request->l_age, $request->l_contact, $request->l_address,
                $request->l_email, $request->l_referral, $request->l_gender, 
                $request->l_branch, $request->l_course, $request->l_year, $request->l_remarks];

            for($x = 0; $x<count($edit_fields); $x++){
                if($student_fields[$x] != $request_fields[$x]){
                    
                    $edit_history = new student_edit_history;
                    $edit_history->stud_id = $student->id;
                    $edit_history->field = $edit_fields[$x];
                    if($edit_fields[$x] == 'Referred By' || $edit_fields[$x] == 'Branch' ||
                        $edit_fields[$x] == 'Course' || $edit_fields[$x] == 'Year'){
                        if($student_fields[$x] == null){
                            $prev = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Referred By'){
                                $prev = employee::where('id', $student_fields[$x])->pluck('fname');
                            }
                            else if($edit_fields[$x] == 'Branch'){
                                $prev = branch::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $prev = course::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Year'){
                                $prev = departure_year::where('id', $student_fields[$x])->pluck('name');
                            }
                            $prev = $prev[0];
                        }

                        $edit_history->previous = $prev[0];

                        if($request_fields[$x] == null){
                            $new = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Referred By'){
                                $new = employee::where('id', $student_fields[$x])->pluck('fname');
                            }
                            else if($edit_fields[$x] == 'Branch'){
                                $new = branch::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $new = course::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Year'){
                                $new = departure_year::where('id', $request_fields[$x])->pluck('name');
                            }
                            $new = $new[0];
                        }

                        $edit_history->new = $new[0];
                    }
                    else{
                        $edit_history->previous = (isset($student_fields[$x])) ? (string) $student_fields[$x] : 'N/A';
                        $edit_history->new = (isset($request_fields[$x])) ? (string) $request_fields[$x] : 'N/A';
                    }
                    $edit_history->edited_by = $edited_by;
                    $edit_history->save();
                }
            }
        }
        
        $student->fname = $request->l_fname;
        $student->mname = $request->l_mname;
        $student->lname = $request->l_lname;
        $student->birthdate = Carbon::parse($request->l_birthdate);
        $student->age = $request->l_age;
        $student->contact = $request->l_contact;

        $program = program::where('name', 'Language Only')->first();
        $student->program_id = $program->id;

        $student->address = $request->l_address;
        $student->email = $request->l_email;
        $student->referral_id = $request->l_referral;
        $student->date_of_signup = Carbon::now();
        $student->gender = $request->l_gender;
        $student->branch_id = $request->l_branch;
        $student->course_id = $request->l_course;
        $student->departure_year_id = $request->l_year;
        $student->remarks = $request->l_remarks;
        $student->save();
    }

    public function save_ssv_student(Request $request){
        $add_edit = $request->s_add_edit;
        $type = $request->s_student_type;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
        }
        else{
            $id = $request->s_id;
            $student = student::find($id);
            $edited_by = Auth::user()->emp_id;
        }

        if(isset($edited_by)){
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date',
                'Age', 'Contact #', 'Program', 'Benefactor', 'Address', 'Email',
                'Referred By', 'Gender', 'Branch', 'Course', 'Year', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname,
                $student->birthdate, $student->age, $student->contact, $student->program_id,
                $student->benefactor_id, $student->address, $student->email,
                $student->referral_id, $student->gender, $student->branch_id,
                $student->course_id, $student->departure_year_id, $student->remarks];

            $request_fields = [$request->s_fname, $request->s_mname, $request->s_lname,
                $request->s_birthdate, $request->s_age, $request->s_contact, $request->s_program,
                $request->s_benefactor, $request->s_address, $request->s_email,
                $request->s_referral, $request->s_gender, $request->s_branch,
                $request->s_course, $request->s_year, $request->s_remarks];
        
            for($x = 0; $x<count($edit_fields); $x++){
                if($student_fields[$x] != $request_fields[$x]){

                    $edit_history = new student_edit_history;
                    $edit_history->stud_id = $student->id;
                    $edit_history->field = $edit_fields[$x];
                    if($edit_fields[$x] == 'Program' || $edit_fields[$x] == 'Benefactor' || 
                        $edit_fields[$x] == 'Referred By' || $edit_fields[$x] == 'Branch' || 
                        $edit_fields[$x] == 'Course' || $edit_fields[$x] == 'Year'){
                        if($student_fields[$x] == null){
                            $prev = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Program'){
                                $prev = program::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Benefactor'){
                                $prev = benefactor::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Referred By'){
                                $prev = employee::where('id', $student_fields[$x])->pluck('fname');
                            }
                            else if($edit_fields[$x] == 'Branch'){
                                $prev = branch::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $prev = course::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Year'){
                                $prev = departure_year::where('id', $student_fields[$x])->pluck('name');
                            }
                            $prev = $prev[0];
                        }
                        $edit_history->previous = $prev;

                        if($request_fields[$x] == null){
                            $new = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Program'){
                                $new = program::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Benefactor'){
                                $new = benefactor::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Referred By'){
                                $new = employee::where('id', $request_fields[$x])->pluck('fname');
                            }
                            else if($edit_fields[$x] == 'Branch'){
                                $new = branch::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $new = course::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Year'){
                                $new = departure_year::where('id', $request_fields[$x])->pluck('name');
                            }
                            $new = $new[0];
                        }

                        $edit_history->new = $new;                  
                    }
                    else{
                        $edit_history->previous = (isset($student_fields[$x])) ? (string) $student_fields[$x] : 'N/A';
                        $edit_history->new = (isset($request_fields[$x])) ? (string) $request_fields[$x] : 'N/A';
                    }
                    $edit_history->edited_by = $edited_by;
                    $edit_history->save();
                }
            }
        }

        $student->fname = $request->s_fname;
        $student->mname = $request->s_mname;
        $student->lname = $request->s_lname;
        $student->birthdate = Carbon::parse($request->s_birthdate);
        $student->age = $request->s_age;
        $student->contact = $request->s_contact;
        $student->program_id = $request->s_program;
        $student->benefactor_id = $request->s_benefactor;
        $student->address = $request->s_address;
        $student->email = $request->s_email;
        $student->referral_id = $request->s_referral;
        $student->gender = $request->s_gender;
        $student->branch_id = $request->s_branch;
        $student->course_id = $request->s_course;
        $student->departure_year_id = $request->s_year;
        $student->remarks = $request->s_remarks;
        $student->save();
    }

    public function get_student(Request $request){
        $id = $request->id;
        $student = student::with('program', 'school', 'benefactor', 'referral', 'branch', 'course', 'departure_year', 'departure_month')->find($id);
        
        return $student;
    }

    public function delete_student(Request $request){
        $deleted_by = Auth::user()->emp_id;
        $student = student::find($request->id);
        $student->delete();

        $delete_history = new student_delete_history;
        $delete_history->stud_id = $student->id;
        $delete_history->deleted_by = $deleted_by;
        $delete_history->save();
    }

    public function final_student(Request $request){
        $student = student::find($request->id);
        $edited_by = Auth::user()->emp_id;

        $edit_history = new student_edit_history;
        $edit_history->stud_id = $student->id;
        $edit_history->field = 'Status';
        $edit_history->previous = $student->status;
        $edit_history->new = 'Final School';
        $edit_history->edited_by = $edited_by;
        $edit_history->save();

        $student->status = 'Final School';
        $student->save();
    }

    public function backout_student(Request $request){
        $student = student::find($request->id);
        $edited_by = Auth::user()->emp_id;

        $edit_history = new student_edit_history;
        $edit_history->stud_id = $student->id;
        $edit_history->field = 'Status';
        $edit_history->previous = $student->status;
        $edit_history->new = 'Back Out';
        $edit_history->edited_by = $edited_by;
        $edit_history->save();

        $student->status = 'Back Out';
        $student->save();
    }

    public function continue_student(Request $request){
        $student = student::find($request->id);
        $edited_by = Auth::user()->emp_id;

        $edit_history = new student_edit_history;
        $edit_history->stud_id = $student->id;
        $edit_history->field = 'Status';
        $edit_history->previous = $student->status;
        $edit_history->new = 'Active';
        $edit_history->edited_by = $edited_by;
        $edit_history->save();

        $edit_history = new student_edit_history;
        $edit_history->stud_id = $student->id;
        $edit_history->field = 'COE Status';
        $edit_history->previous = $student->coe_status;
        $edit_history->new = 'TBA';
        $edit_history->edited_by = $edited_by;
        $edit_history->save();

        $student->status = 'Active';
        $student->coe_status = 'TBA';
        $student->save();
    }

    public function approve_student(Request $request){
        $student = student::find($request->id);
        $edited_by = Auth::user()->emp_id;

        $edit_history = new student_edit_history;
        $edit_history->stud_id = $student->id;
        $edit_history->field = 'COE Status';
        $edit_history->previous = $student->coe_status;
        $edit_history->new = 'Approved';
        $edit_history->edited_by = $edited_by;
        $edit_history->save();

        $student->coe_status = 'Approved';
        $student->save();
    }

    public function deny_student(Request $request){
        $student = student::find($request->id);
        $edited_by = Auth::user()->emp_id;

        $edit_history = new student_edit_history;
        $edit_history->stud_id = $student->id;
        $edit_history->field = 'Status';
        $edit_history->previous = $student->coe_status;
        $edit_history->new = 'Denied';
        $edit_history->edited_by = $edited_by;
        $edit_history->save();

        $student->coe_status = 'Denied';
        $student->save();
    }

    public function cancel_student(Request $request){
        $student = student::find($request->id);
        $edited_by = Auth::user()->emp_id;

        $edit_history = new student_edit_history;
        $edit_history->stud_id = $student->id;
        $edit_history->field = 'Status';
        $edit_history->previous = $student->status;
        $edit_history->new = 'Cancelled';
        $edit_history->edited_by = $edited_by;
        $edit_history->save();

        $student->status = 'Cancelled';
        $student->save();
    }

    public function view_profile(Request $request){
        $id = $request->id;
        $student = student::with('program', 'school', 'benefactor', 'referral', 'branch', 'course', 'departure_year', 'departure_month')->find($id);
        
        return $student;
    }

    public function course_all(Request $request){
        $course = course::where('name', 'LIKE', '%'.$request->name.'%')->get()->toArray();

        $array = [];
        foreach ($course as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function program_all(Request $request){
        $program = program::where('name', 'LIKE', '%'.$request->name.'%')
            ->where('name', '<>', 'Language Only')->where('name', '<>', 'SSV (Careworker)')
            ->where('name', '<>', 'SSV (Hospitality)')->get()->toArray();

        $array = [];
        foreach ($program as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function program_ssv(Request $request){
        $program = program::where('name', 'LIKE', '%'.$request->name.'%')
            ->where('name', '=', 'SSV (Careworker)')->orWhere('name', '=', 'SSV (Hospitality)')->get()->toArray();

        $array = [];
        foreach ($program as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function school_all(Request $request){
        $school = school::where('name', 'LIKE', '%'.$request->name.'%')->get()->toArray();

        $array = [];
        foreach ($school as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function benefactor_all(Request $request){
        $benefactor = benefactor::where('name', 'LIKE', '%'.$request->name.'%')->get()->toArray();

        $array = [];
        foreach ($benefactor as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }
}
