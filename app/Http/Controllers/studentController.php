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
use App\student_add_history;
use App\student_edit_history;
use App\student_delete_history;
use App\company;
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
        $company = company::all();

        return view('pages.students', compact('program', 'school', 'benefactor', 
        'employee', 'branch', 'course', 'departure_year', 'departure_month', 'company'));
    }

    public function branch(Request $request){
        $current_branch = $request->current_branch;
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;
        $except = ['Language Only', 'Trainee', 'SSW (Careworker)', 'SSW (Hospitality)'];

        $branch = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')
        ->whereHas('program', function($query) use($except){
            $query->whereNotIn('name', $except);
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })->orderBy('school_id')->get();

        $branch = $branch->where('branch.name', $current_branch)->whereIn('status', ['Active', 'Final School']);

        return $this->refreshDatatable($branch);
    }

    public function refreshDatatable($branch){
        return Datatables::of($branch)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';

            if(canAccessAll()){
                if($data->status == 'Final School'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>&nbsp;';
                }
                else if($data->status == 'Active'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Final School" class="btn btn-success btn-xs final_student" id="'.$data->id.'"><i class="fa fa-user-graduate"></i></button>&nbsp;';
                }
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
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
        $except = ['Language Only', 'Trainee', 'SSW (Careworker)', 'SSW (Hospitality)'];

        $status = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')
        ->whereHas('program', function($query) use($except){
            $query->whereNotIn('name', $except);
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($current_status == 'Back Out / Cancelled', function($query) use($current_status){
            $query->whereIn('status', ['Back Out', 'Cancelled']);
        }, function($query) use($current_status){
            $query->where('status', $current_status);
        })->orderBy('school_id')->get();
        
        return $this->refreshDatatableStatus($status);
    }

    public function refreshDatatableStatus($type){
        return Datatables::of($type)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';   

            if(canAccessAll()){
                if($data->status == 'Final School'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>&nbsp;';
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
                }
                else if($data->status == 'Back Out' || $data->status == 'Cancelled'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>&nbsp;';
                }
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
        $except = ['Language Only', 'Trainee', 'SSW (Careworker)', 'SSW (Hospitality)'];

        $result = student::with('program', 'school', 'referral', 
        'branch', 'course', 'departure_year', 'departure_month')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })->whereIn('status', ['Final School', 'Cancelled'])->orderBy('school_id')->get();

        return $this->refreshDatatableResult($result);
    }

    public function refreshDatatableResult($result){
        return Datatables::of($result)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-info btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>&nbsp;';
                if($data->coe_status != 'Approved'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Approved" class="btn btn-success btn-xs approve_student" id="'.$data->id.'"><i class="fa fa-check"></i></button>&nbsp;';
                }
                if($data->coe_status != 'Denied'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Denied" class="btn btn-danger btn-xs deny_student" id="'.$data->id.'"><i class="fa fa-times"></i></button>&nbsp;';
                }
                if($data->status != 'Cancelled'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Cancelled" class="btn btn-warning btn-xs cancel_student" id="'.$data->id.'"><i class="fa fa-ban"></i></button>&nbsp;';
                }
                
            }

            return  $html;
        })
        ->make(true);
    }

    public function language(Request $request){
        $departure_year = $request->departure_year;

        $language = student::with('program', 'referral', 'branch', 'course', 'departure_year')
        ->whereHas('program', function($query){
            $query->where('name', 'Language Only');
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->get();

        return $this->refreshDatatableLanguage($language);
    }

    public function refreshDatatableLanguage($language){
        return Datatables::of($language)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_language_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            return  $html;
        })
        ->make(true);
    }

    public function all(){
        $all = student::with('branch', 'program', 'school', 'benefactor', 'company', 'course', 'referral')->get();

        return $this->refreshDatatableAll($all);
    }

    public function refreshDatatableAll($all){
        return Datatables::of($all)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            
            if(isset($data->program)){
                if($data->program->name == 'SSW (Careworker)' || $data->program->name == 'SSW (Hospitality)'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_ssw_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                }
                else if($data->program->name == 'Language Only'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_language_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                }
                else{
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                }
            }
            else{
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            }

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            
            return  $html;
        })
        ->make(true);
    }

    public function ssw(Request $request){
        info($request   );
        $departure_year = $request->departure_year;
        $current_ssw = $request->current_ssw;

        $ssw = student::with('program', 'benefactor', 'referral', 'course', 'departure_year')
            ->whereHas('program', function($query) use ($request) {
                $query->where('name', 'SSW (Careworker)')->orWhere('name', 'SSW (Hospitality)');
            })
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })->get();

        if($current_ssw == 'SSW'){
            $ssw = $ssw->where('status', 'Active');
        }
        else if($current_ssw = 'Back Out'){
            $ssw = $ssw->where('status', 'Back Out');
        }

        return $this->refreshDatatableSSW($ssw);
    }

    public function refreshDatatableSSW($ssw){
        return Datatables::of($ssw)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_ssw_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';

            if(canAccessAll()){
                if($data->status == 'Active'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
                }
                else if($data->status == 'Back Out'){
                    
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>&nbsp;';
                }
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>&nbsp;';
            }
            return  $html;
        })
        ->make(true);
    }

    public function trainee(Request $request){
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;
        $current_trainee = $request->current_trainee;

        $trainee = student::with('company', 'course')
            ->whereHas('program', function($query){
                $query->where('name', 'Trainee');
            })->when($current_trainee == 'Trainee', function($query){
                $query->where('status', 'Active');
            })->when($current_trainee == 'Back Out', function($query){
                $query->where('status', 'Back Out');
            })->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })->get();

        return Datatables::of($trainee)
            ->editColumn('name', function($data){
                return $data->lname.', '.$data->fname.' '.$data->mname;
            })
            ->editColumn('birthdate', function($data){
                return getAge($data->birthdate);
            })
            ->editColumn('coe_status', function($data){
                if($data->coe_status == 'Approved'){
                    return 'Passed';
                }else if($data->coe_status == 'Denied'){
                    return 'Failed';
                }
                return $data->coe_status;
            })
            ->addColumn('action', function($data){
                $html = '';

                if(canAccessAll()){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>&nbsp;';
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_trainee_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';   
    
                    if($data->coe_status == 'Approved' || $data->coe_status == 'Denied' || $data->status == 'Back Out'){
                        $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-default btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>&nbsp;';
                    }
                    if($data->coe_status != 'Approved'){
                        $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Passed" class="btn btn-success btn-xs approve_student" id="'.$data->id.'"><i class="fa fa-check"></i></button>&nbsp;';
                    }
                    if($data->coe_status != 'Denied'){
                        $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Failed" class="btn btn-danger btn-xs deny_student" id="'.$data->id.'"><i class="fa fa-times"></i></button>&nbsp;';
                    }
                    if($data->status != 'Back Out'){
                        $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>&nbsp;';
                    }
                }

                return $html;
            })
            ->make(true);
    }

    public function save_student(Request $request){
        if($request->hasFile('picture')){
            $fileextension = $request->picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg'){
                return false;
            }
        }

        $add_edit = $request->add_edit;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
            $added_by = Auth::user()->emp_id;
        }
        else{
            $id = $request->id;
            $student = student::find($id);
            $edited_by = Auth::user()->emp_id;
        }

        // EDIT HISTORY -- START

        if(isset($edited_by)){
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date', 'Contact #', 
                'Program', 'School', 'Benefactor', 'Address', 'Email', 'Referred By', 'Sign Up Date', 
                'Medical Date', 'Completion Date', 'Gender', 'Branch', 'Course', 'Year', 'Month', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname, $student->birthdate,
                $student->contact, $student->program_id, $student->school_id, $student->benefactor_id, 
                $student->address, $student->email, $student->referral_id, $student->date_of_signup, $student->date_of_medical, 
                $student->date_of_completion, $student->gender, $student->branch_id, $student->course_id, 
                $student->departure_year_id, $student->departure_month_id, $student->remarks];

            $request_fields = [$request->fname, $request->mname, $request->lname, $request->birthdate,
                $request->contact, $request->program, $request->school, $request->benefactor, 
                $request->address, $request->email, $request->referral, $request->sign_up, 
                $request->medical, $request->completion, $request->gender, $request->branch, 
                $request->course, $request->year, $request->month, $request->remarks];
        }

        // EDIT HISTORY -- END

        $student->fname = $request->fname;
        $student->mname = $request->mname;
        $student->lname = $request->lname;
        $student->birthdate = Carbon::parse($request->birthdate);
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

        // ADD HISTORY -- START

        if(isset($added_by)){
            $add_history = new student_add_history;
            $add_history->stud_id = $student->id;
            $add_history->type = 'Student';
            $add_history->added_by = $added_by;
            $add_history->save();
        }

        // ADD HISTORY -- END

        // EDIT HISTORY PT. 2 -- START

        if(isset($edited_by)){
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
                                $prev = employee::where('id', $student_fields[$x])->withTrashed()->pluck('fname');
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
                                $new = employee::where('id', $request_fields[$x])->withTrashed()->pluck('fname');
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

        // EDIT HISTORY PT. 2 -- END

        if($request->hasFile('picture')){
            $fileextension = $request->picture->getClientOriginalExtension();
            $encryption = sha1(time().$request->picture->getClientOriginalName());
            $filename = $encryption.'.'.$fileextension;

            $request->picture->storeAs('public/img/student', $filename);

            $prev = $student->picture;
            $student->picture = $filename;

            $student->save();

            if(isset($edited_by)){
                $edit_history = new student_edit_history;
                $edit_history->stud_id = $student->id;
                $edit_history->field = 'Picture';
                $edit_history->previous = 'Uploaded';
                $edit_history->new = 'New Picture';
                $edit_history->edited_by = $edited_by;
                $edit_history->save();
            }
        }

        return $student->id;
    }

    public function save_language_student(Request $request){
        if($request->hasFile('l_picture')){
            $fileextension = $request->l_picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg'){
                return false;
            }
        }

        $add_edit = $request->l_add_edit;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
            $added_by = Auth::user()->emp_id;
        }
        else{
            $id = $request->l_id;
            $student = student::find($id);
            $edited_by = Auth::user()->emp_id;
        }
        
        if(isset($edited_by)){
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date',
                'Contact #', 'Address', 'Email', 'Referred By', 'Sign Up Date',
                'Gender', 'Branch', 'Course', 'Year', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname,
                $student->birthdate, $student->contact, $student->address,
                $student->email, $student->referral_id, $student->date_of_signup,
                $student->gender, $student->branch_id, $student->course_id,
                $student->departure_year_id, $student->remarks];

            $request_fields = [$request->l_fname, $request->l_mname, $request->l_lname,
                $request->l_birthdate, $request->l_contact, $request->l_address,
                $request->l_email, $request->l_referral, $request->l_sign_up, $request->l_gender, 
                $request->l_branch, $request->l_course, $request->l_year, $request->l_remarks];
        }
        
        $student->fname = $request->l_fname;
        $student->mname = $request->l_mname;
        $student->lname = $request->l_lname;
        $student->birthdate = Carbon::parse($request->l_birthdate);
        $student->contact = $request->l_contact;

        $program = program::where('name', 'Language Only')->first();
        $student->program_id = $program->id;

        $student->address = $request->l_address;
        $student->email = $request->l_email;
        $student->referral_id = $request->l_referral;
        $student->date_of_signup = Carbon::parse($request->l_sign_up);
        $student->gender = $request->l_gender;
        $student->branch_id = $request->l_branch;
        $student->course_id = $request->l_course;
        $student->departure_year_id = $request->l_year;
        $student->remarks = $request->l_remarks;
        $student->save();

        // ADD HISTORY -- START

        if(isset($added_by)){
            $add_history = new student_add_history;
            $add_history->stud_id = $student->id;
            $add_history->type = 'Language Only';
            $add_history->added_by = $added_by;
            $add_history->save();
        }

        // ADD HISTORY -- END

        // EDIT HISTORY PT. 2 -- START

        if(isset($edited_by)){
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

        // EDIT HISTORY PT. 2 -- END

        if($request->hasFile('l_picture')){
            $fileextension = $request->l_picture->getClientOriginalExtension();
            $encryption = sha1(time().$request->l_picture->getClientOriginalName());
            $filename = $encryption.'.'.$fileextension;

            $request->l_picture->storeAs('public/img/student', $filename);

            $student->picture = $filename;

            $student->save();
        }

        return $student->id;
    }

    public function save_ssw_student(Request $request){
        if($request->hasFile('s_picture')){
            $fileextension = $request->s_picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg'){
                return false;
            }
        }

        $add_edit = $request->s_add_edit;

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
            $added_by = Auth::user()->emp_id;
        }
        else{
            $id = $request->s_id;
            $student = student::find($id);
            $edited_by = Auth::user()->emp_id;
        }

        if(isset($edited_by)){
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date',
                'Contact #', 'Program', 'Benefactor', 'Address', 'Email', 'Referred By', 
                'Sign Up Date', 'Gender', 'Course', 'Year', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname,
                $student->birthdate, $student->contact, $student->program_id,
                $student->benefactor_id, $student->address, $student->email,
                $student->referral_id, $student->date_of_signup, $student->gender,
                $student->course_id, $student->departure_year_id, $student->remarks];

            $request_fields = [$request->s_fname, $request->s_mname, $request->s_lname,
                $request->s_birthdate, $request->s_contact, $request->s_program,
                $request->s_benefactor, $request->s_address, $request->s_email,
                $request->s_referral, $request->s_sign_up, $request->s_gender,
                $request->s_course, $request->s_year, $request->s_remarks];
        }

        $student->fname = $request->s_fname;
        $student->mname = $request->s_mname;
        $student->lname = $request->s_lname;
        $student->birthdate = Carbon::parse($request->s_birthdate);
        $student->contact = $request->s_contact;
        $student->program_id = $request->s_program;
        $student->benefactor_id = $request->s_benefactor;
        $student->address = $request->s_address;
        $student->email = $request->s_email;
        $student->referral_id = $request->s_referral;
        $student->date_of_signup = Carbon::parse($request->s_sign_up);
        $student->gender = $request->s_gender;
        $student->branch_id = 1; // Makati ID
        $student->course_id = $request->s_course;
        $student->departure_year_id = $request->s_year;
        $student->remarks = $request->s_remarks;
        $student->save();
        
        // ADD HISTORY -- START

        if(isset($added_by)){
            $add_history = new student_add_history;
            $add_history->stud_id = $student->id;
            $add_history->type = 'SSW';
            $add_history->added_by = $added_by;
            $add_history->save();
        }

        // ADD HISTORY -- END

        // EDIT HISTORY PT. 2 -- START

        if(isset($edited_by)){
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
        
        // EDIT HISTORY PT. 2 -- END

        if($request->hasFile('s_picture')){
            $fileextension = $request->s_picture->getClientOriginalExtension();
            $encryption = sha1(time().$request->s_picture->getClientOriginalName());
            $filename = $encryption.'.'.$fileextension;

            $request->s_picture->storeAs('public/img/student', $filename);

            $student->picture = $filename;

            $student->save();
        }

        return $student->id;
    }

    public function save_trainee_student(Request $request){
        if($request->hasFile('t_picture')){
            $fileextension = $request->t_picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg'){
                return false;
            }
        }

        $add_edit = $request->t_add_edit;
        $trainee_program = program::where('name', 'Trainee')->first();

        if($add_edit == 'add'){
            $student = new student;
            $student->status = 'Active';
            $student->coe_status = 'TBA';
            $added_by = Auth::user()->emp_id;
        }
        else{
            $id = $request->t_id;
            $student = student::find($id);
            $edited_by = Auth::user()->emp_id;
        }

        if(isset($edited_by)){
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Company',
                'Contact #', 'Gender', 'Birth Date', 'Course', 'Email', 'Address',
                'Year', 'Month', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname,
                $student->company_id, $student->contact, $student->gender, $student->birthdate,
                $student->course_id, $student->email, $student->address, $student->departure_year_id,
                $student->departure_month_id, $student->remarks];

            $request_fields = [$request->t_fname, $request->t_mname, $request->t_lname,
                $request->t_company, $request->t_contact, $request->t_gender, $request->t_birthdate,
                $request->t_course, $request->t_email, $request->t_address, $request->t_year,
                $request->t_month, $request->t_remarks];
        }

        $student->fname = $request->t_fname;
        $student->mname = $request->t_mname;
        $student->lname = $request->t_lname;
        $student->program_id = $trainee_program->id;
        $student->company_id = $request->t_company;
        $student->contact = $request->t_contact;
        $student->gender = $request->t_gender;
        $student->birthdate = Carbon::parse($request->t_birthdate);
        $student->course_id = $request->t_course;
        $student->email = $request->t_email;
        $student->address = $request->t_address;
        $student->departure_year_id = $request->t_year;
        $student->departure_month_id = $request->t_month;
        $student->remarks = $request->t_remarks;
        
        $student->referral_id = 2; // sir Aris' ID
        $student->branch_id = 1; // Makati ID
        $student->date_of_signup = Carbon::now();
        $student->save();

        // ADD HISTORY -- START

        if(isset($added_by)){
            $add_history = new student_add_history;
            $add_history->stud_id = $student->id;
            $add_history->type = 'Trainee';
            $add_history->added_by = $added_by;
            $add_history->save();
        }

        // ADD HISTORY -- END

        // EDIT HISTORY PT. 2 -- START

        if(isset($edited_by)){
            for($x = 0; $x<count($edit_fields); $x++){
                if($student_fields[$x] != $request_fields[$x]){
    
                    $edit_history = new student_edit_history;
                    $edit_history->stud_id = $student->id;
                    $edit_history->field = $edit_fields[$x];
                    if($edit_fields[$x] == 'Company' || $edit_fields[$x] == 'Course' || 
                        $edit_fields[$x] == 'Year' || $edit_fields[$x] == 'Month'){
                        if($student_fields[$x] == null){
                            $prev = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Company'){
                                $prev = company::where('id', $student_fields[$x])->pluck('name');
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
                        }else{
                            if($edit_fields[$x] == 'Company'){
                                $new = company::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Referred By'){
                                $new = employee::where('id', $request_fields[$x])->pluck('fname');
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
        
        // EDIT HISTORY PT. 2 -- END

        if($request->hasFile('t_picture')){
            $fileextension = $request->s_picture->getClientOriginalExtension();
            $encryption = sha1(time().$request->t_picture->getClientOriginalName());
            $filename = $encryption.'.'.$fileextension;

            $request->t_picture->storeAs('public/img/student', $filename);

            $student->picture = $filename;

            $student->save();
        }

        return $student->id;
    }

    public function get_student(Request $request){
        $id = $request->id;
        $student = student::with('program', 'school', 'benefactor', 'company', 'referral', 'branch', 'course', 'departure_year', 'departure_month')->find($id);
        
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

        return $student->id;
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

        return $student->id;
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

        return $student->id;
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

        return $student->id;
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

        return $student->id;
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

        return $student->id;
    }

    public function view_profile(Request $request){
        $id = $request->id;
        $student = student::with('program', 'school', 'benefactor', 'company', 'referral', 'branch', 'course', 'departure_year', 'departure_month')->find($id);
        
        $birth = new Carbon($student->birthdate);
        $student->age = $birth->diffInYears(Carbon::now());

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
            ->where('name', '<>', 'Language Only')->where('name', '<>', 'SSW (Careworker)')
            ->where('name', '<>', 'SSW (Hospitality)')->get()->toArray();

        $array = [];
        foreach ($program as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function program_ssw(Request $request){
        $program = program::where('name', 'LIKE', '%'.$request->name.'%')
            ->where('name', '=', 'SSW (Careworker)')->orWhere('name', '=', 'SSW (Hospitality)')->get()->toArray();

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

    public function company_all(Request $request){
        $company = company::where('name', 'LIKE', '%'.$request->name.'%')->get()->toArray();

        $array = [];
        foreach ($company as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }
}
