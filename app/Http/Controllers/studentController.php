<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
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
use App\university;
use App\company;
use App\student_emergency;
use App\student_emp_history;
use App\student_educational_background;
use Carbon\Carbon;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Redirect;
use Intervention\Image\ImageManagerStatic as Image;

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
        $university = university::all();
        $batch = student::whereNotNull('batch')->orderBy('batch')->groupBy('batch')->pluck('batch');

        return view('pages.students', compact('program', 'school', 'benefactor', 'batch', 
        'employee', 'branch', 'course', 'departure_year', 'departure_month', 'company', 'university'));
    }

    public function branch(Request $request){//Makati, Cebu, Davao
        $current_branch = $request->current_branch;
        $dep_year = $request->departure_year;
        $dep_month = $request->departure_month;

        $branch = student::student($dep_year, $dep_month)->get();

        $branch = $branch->where('branch.name', $current_branch)->whereIn('status', ['Active', 'Final School']);

        return Datatables::of($branch)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('school', function($data){
            if(StudentHigherPermission()){
                return ($data->school) ? $data->school->name : '';
            }
        })
        ->editColumn('benefactor', function($data){
            if(StudentHigherPermission()){
                return ($data->benefactor) ? $data->benefactor->name : '';
            }
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
            
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';

            if(canAccessAll() || canEditFinalSchool()){
                if($data->status == 'Final School'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>';
                }
                else if($data->status == 'Active'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Final School" class="btn btn-success btn-xs final_student" id="'.$data->id.'"><i class="fa fa-user-graduate"></i></button>';
                }
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>';    
            }

            if(StudentHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Other Info" class="btn btn-default btn-xs info_student" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
            }

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';    
            }
            return  $html;
        })
        ->make(true);
    }

    public function status(Request $request){//Final School, Back Out / Cancelled
        $current_status = $request->current_status;
        $dep_year = $request->departure_year;
        $dep_month = $request->departure_month;
        $except = ['Language Only', 'TITP', 'TITP (Careworker)', 'SSW (Careworker)', 'SSW (Hospitality)'];
        $except = program::whereIn('name', $except)->pluck('id');

        $status = student::student($dep_year, $dep_month)
        ->when($current_status == 'Back Out / Cancelled', function($query) use($current_status){
            $query->whereIn('status', ['Back Out', 'Cancelled']);
        }, function($query) use($current_status){
            $query->where('status', $current_status);
        })->get();
        
        return Datatables::of($status)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('school', function($data){
            if(StudentHigherPermission()){
                return ($data->school) ? $data->school->name : '';
            }
        })
        ->editColumn('benefactor', function($data){
            if(StudentHigherPermission()){
                return ($data->benefactor) ? $data->benefactor->name : '';
            }
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';   

            if(canAccessAll() || canEditFinalSchool()){
                if($data->status == 'Final School'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>';
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>';
                }
                else if($data->status == 'Back Out' || $data->status == 'Cancelled'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>';
                }
            }
            
            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }
            return $html;
        })
        ->make(true);
    }

    public function result(Request $request){//Result Monitoring
        $dep_year = $request->departure_year;
        $dep_month = $request->departure_month;

        $result = student::student($dep_year, $dep_month)
        ->whereIn('status', ['Final School', 'Cancelled'])->get();

        return Datatables::of($result)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('school', function($data){
            if(StudentHigherPermission()){
                return ($data->school) ? $data->school->name : '';
            }
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-info btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>';
                if($data->coe_status != 'Approved'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Approved" class="btn btn-success btn-xs approve_student" id="'.$data->id.'"><i class="fa fa-check"></i></button>';
                }
                if($data->coe_status != 'Denied'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Denied" class="btn btn-danger btn-xs deny_student" id="'.$data->id.'"><i class="fa fa-times"></i></button>';
                }
                if($data->status != 'Cancelled'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Cancelled" class="btn btn-warning btn-xs cancel_student" id="'.$data->id.'"><i class="fa fa-ban"></i></button>';
                }
                
            }

            if(StudentHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Other Info" class="btn btn-default btn-xs info_student" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
            }

            return  $html;
        })
        ->make(true);
    }

    public function language(Request $request){//Language Only
        $dep_year = $request->departure_year;

        $language = student::with('program', 'referral', 'branch', 'course', 'departure_year')
        ->whereHas('program', function($query){
            $query->where('name', 'Language Only');
        })
        ->when($departure_year != 'All', function($query) use($dep_year){
            $query->where('departure_year_id', $dep_year);
        })
        ->get();

        return Datatables::of($language)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_language_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            
            if(StudentHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Other Info" class="btn btn-default btn-xs info_student" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
            }

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }
            return  $html;
        })
        ->make(true);
    }

    public function all(){
        $all = student::with('branch', 'program', 'school', 'benefactor', 'company', 'course', 'referral')->get();

        return Datatables::of($all)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('school', function($data){
            if(StudentHigherPermission()){
                return ($data->school) ? $data->school->name : '';
            }
        })
        ->editColumn('benefactor', function($data){
            if(StudentHigherPermission()){
                return ($data->benefactor) ? $data->benefactor->name : '';
            }
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
            
            if(isset($data->program)){
                if($data->program->name == 'SSW (Careworker)' || $data->program->name == 'SSW (Hospitality)'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_ssw_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
                }
                else if($data->program->name == 'Language Only'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_language_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
                }
                else{
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
                }
            }
            else{
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            }

            if(StudentHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Other Info" class="btn btn-default btn-xs info_student" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
            }

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }
            
            return  $html;
        })
        ->make(true);
    }

    public function ssw(Request $request){
        $departure_year = $request->departure_year;
        $current_ssw = $request->current_ssw;
        $batch = $request->batch;

        $ssw = student::with('program', 'benefactor', 'referral', 'course', 'departure_year')
            ->whereHas('program', function($query) use ($request) {
                $query->where('name', 'SSW (Careworker)')->orWhere('name', 'SSW (Hospitality)');
            })
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($batch != 'All', function($query) use($batch){
                $query->where('batch', $batch);
            })->get();

        if($current_ssw == 'SSW'){
            $ssw = $ssw->where('status', 'Active');
        }
        else if($current_ssw = 'Back Out'){
            $ssw = $ssw->where('status', 'Back Out');
        }

        return Datatables::of($ssw)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->editColumn('benefactor', function($data){
            if(StudentHigherPermission()){
                return ($data->benefactor) ? $data->benefactor->name : '';
            }
        })
        ->editColumn('birthdate', function($data){
            return getAge($data->birthdate);
        })->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_ssw_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';

            if(canAccessAll()){
                if($data->status == 'Active'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>';
                }
                else if($data->status == 'Back Out'){
                    
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>';
                }
            }

            if(StudentHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Other Info" class="btn btn-default btn-xs info_student" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
            }

            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }
            return  $html;
        })
        ->make(true);
    }

    public function titp(Request $request){
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;
        $current_titp = $request->current_titp;

        $titp = student::with('program', 'company', 'course')
            ->whereHas('program', function($query){
                $query->where('name', 'TITP')->orWhere('name', 'TITP (Careworker)');
            })->when($current_titp == 'TITP', function($query){
                $query->where('status', 'Active');
            })->when($current_titp == 'Back Out', function($query){
                $query->where('status', 'Back Out');
            })->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })->get();

        return Datatables::of($titp)
            ->editColumn('name', function($data){
                return $data->lname.', '.$data->fname.' '.$data->mname;
            })
            ->editColumn('company', function($data){
                if(StudentHigherPermission()){
                    return ($data->company) ? $data->company->name : '';
                }
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
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_titp_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';   
    
                    if($data->coe_status == 'Approved' || $data->coe_status == 'Denied' || $data->status == 'Back Out'){
                        $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-warning btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-step-backward"></i></button>';
                    }else{
                        if($data->coe_status != 'Approved'){
                            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Passed" class="btn btn-success btn-xs approve_student" id="'.$data->id.'"><i class="fa fa-check"></i></button>';
                        }
                        if($data->coe_status != 'Denied'){
                            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Failed" class="btn btn-danger btn-xs deny_student" id="'.$data->id.'"><i class="fa fa-times"></i></button>';
                        }
                        if($data->status != 'Back Out'){
                            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>';
                        }
                    }
                }

                if(StudentHigherPermission()){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Other Info" class="btn btn-default btn-xs info_student" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
                }

                if(canAccessAll()){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
                }

                return $html;
            })
            ->make(true);
    }

    public function intern(Request $request){
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;
        $current_intern = $request->current_intern;

        $intern = student::with('branch', 'program', 'benefactor', 'university', 'course')
            ->whereHas('program', function($query){
                $query->where('name', 'Internship');
            })->when($current_intern == 'Intern', function($query){
                $query->where('status', 'Active');
            })->when($current_intern == 'Back Out', function($query){
                $query->where('status', 'Back Out');
            })->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })->get();

        return Datatables::of($intern)
            ->editColumn('name', function($data){
                return $data->lname.', '.$data->fname.' '.$data->mname;
            })
            ->editColumn('benefactor', function($data){
                if(StudentHigherPermission()){
                    return ($data->benefactor) ? $data->benefactor->name : '';
                }
            })
            ->editColumn('birthdate', function($data){
                return getAge($data->birthdate);
            })
            ->addColumn('action', function($data){
                $html = '';

                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Profile" class="btn btn-primary btn-xs view_profile" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_intern_student" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';   
                
                if(canAccessAll()){
                    if($data->status == 'Active'){
                        $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Back Out" class="btn btn-warning btn-xs backout_student" id="'.$data->id.'"><i class="fa fa-sign-out-alt"></i></button>';
                    }
                    else if($data->status == 'Back Out'){
                        
                        $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Re Apply" class="btn btn-success btn-xs continue_student" id="'.$data->id.'"><i class="fa fa-sign-in-alt"></i></button>';
                    }
                }

                if(StudentHigherPermission()){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Other Info" class="btn btn-default btn-xs info_student" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
                }

                if(canAccessAll()){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_student" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
                }

                return $html;
            })
            ->make(true);
    }

    public function save_student(Request $request){
        if($request->hasFile('picture')){
            $fileextension = $request->picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg' && $fileextension != 'JPG'){
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
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date', 'Civil Status', 'Contact #', 
                'Program', 'School', 'Benefactor', 'Address', 'Email', 'Referred By', 'Sign Up Date', 
                'Medical Date', 'Completion Date', 'Gender', 'Branch', 'Course', 'Year', 'Month', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname, $student->birthdate, $student->civil_status,
                $student->contact, $student->program_id, $student->school_id, $student->benefactor_id, 
                $student->address, $student->email, $student->referral_id, $student->date_of_signup, $student->date_of_medical, 
                $student->date_of_completion, $student->gender, $student->branch_id, $student->course_id, 
                $student->departure_year_id, $student->departure_month_id, $student->remarks];

            $request_fields = [$request->fname, $request->mname, $request->lname, $request->birthdate, $request->civil,
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
        $student->civil_status = $request->civil;
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
            if($student->picture != 'avatar5.png'){
                Storage::delete('public/img/student/'.$student->picture);
            }
            $encryption = sha1(date('jSFY').time().$request->picture->getClientOriginalName());
            $filename = $encryption.'.jpg';

            $target = storage_path().'/storage/img/student/'.$filename;
            Image::make($request->file('picture'))->save($target, 60, 'jpg');

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

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg' && $fileextension != 'JPG'){
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
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date', 'Civil Status',
                'Contact #', 'Address', 'Email', 'Referred By', 'Sign Up Date',
                'Gender', 'Branch', 'Course', 'Year', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname,
                $student->birthdate, $student->civil_status, $student->contact, $student->address,
                $student->email, $student->referral_id, $student->date_of_signup,
                $student->gender, $student->branch_id, $student->course_id,
                $student->departure_year_id, $student->remarks];

            $request_fields = [$request->l_fname, $request->l_mname, $request->l_lname,
                $request->l_birthdate, $request->l_civil, $request->l_contact, $request->l_address,
                $request->l_email, $request->l_referral, $request->l_sign_up, $request->l_gender, 
                $request->l_branch, $request->l_course, $request->l_year, $request->l_remarks];
        }
        
        $student->fname = $request->l_fname;
        $student->mname = $request->l_mname;
        $student->lname = $request->l_lname;
        $student->birthdate = Carbon::parse($request->l_birthdate);
        $student->civil_status = $request->l_civil;
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
            if($student->picture != 'avatar5.png'){
                Storage::delete('public/img/student/'.$student->picture);
            }
            $encryption = sha1(date('jSFY').time().$request->l_picture->getClientOriginalName());
            $filename = $encryption.'.jpg';

            $target = storage_path().'/storage/img/student/'.$filename;
            Image::make($request->file('l_picture'))->save($target, 60, 'jpg');

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

    public function save_ssw_student(Request $request){
        if($request->hasFile('s_picture')){
            $fileextension = $request->s_picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg' && $fileextension != 'JPG'){
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
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Birth Date', 'Civil Status',
                'Contact #', 'Batch', 'Program', 'Benefactor', 'Address', 'Email', 'Referred By', 
                'Sign Up Date', 'Gender', 'Course', 'Year', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname, $student->birthdate,
                $student->civil_status, $student->contact, $student->batch, $student->program_id,
                $student->benefactor_id, $student->address, $student->email,
                $student->referral_id, $student->date_of_signup, $student->gender,
                $student->course_id, $student->departure_year_id, $student->remarks];

            $request_fields = [$request->s_fname, $request->s_mname, $request->s_lname, $request->s_birthdate,
                $request->s_civil, $request->s_contact, $request->s_batch, $request->s_program,
                $request->s_benefactor, $request->s_address, $request->s_email,
                $request->s_referral, $request->s_sign_up, $request->s_gender,
                $request->s_course, $request->s_year, $request->s_remarks];
        }

        $student->fname = $request->s_fname;
        $student->mname = $request->s_mname;
        $student->lname = $request->s_lname;
        $student->birthdate = Carbon::parse($request->s_birthdate);
        $student->civil_status = $request->s_civil;
        $student->contact = $request->s_contact;
        $student->batch = $request->s_batch;
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
            if($student->picture != 'avatar5.png'){
                Storage::delete('public/img/student/'.$student->picture);
            }
            $encryption = sha1(date('jSFY').time().$request->s_picture->getClientOriginalName());
            $filename = $encryption.'.jpg';

            $target = storage_path().'/public/img/student/'.$filename;
            Image::make($request->file('s_picture'))->save($target, 60, 'jpg');

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

    public function save_titp_student(Request $request){
        if($request->hasFile('t_picture')){
            $fileextension = $request->t_picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg' && $fileextension != 'JPG'){
                return false;
            }
        }
        
        $add_edit = $request->t_add_edit;

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
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Program', 'Company',
                'Contact #', 'Gender', 'Birth Date', 'Civil Status', 'Course', 'Email', 'Address',
                'Year', 'Month', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname, $student->program_id,
                $student->company_id, $student->contact, $student->gender, $student->birthdate,
                $student->civil_status, $student->course_id, $student->email, $student->address,
                $student->departure_year_id, $student->departure_month_id, $student->remarks];

            $request_fields = [$request->t_fname, $request->t_mname, $request->t_lname, $request->t_program,
                $request->t_company, $request->t_contact, $request->t_gender, $request->t_birthdate,
                $request->t_civil, $request->t_course, $request->t_email, $request->t_address, $request->t_year,
                $request->t_month, $request->t_remarks];
        }

        $student->fname = $request->t_fname;
        $student->mname = $request->t_mname;
        $student->lname = $request->t_lname;
        $student->program_id = $request->t_program;
        $student->company_id = $request->t_company;
        $student->contact = $request->t_contact;
        $student->gender = $request->t_gender;
        $student->birthdate = Carbon::parse($request->t_birthdate);
        $student->civil_status = $request->t_civil;
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
            $add_history->type = 'TITP';
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
                    if($edit_fields[$x] == 'Program' || $edit_fields[$x] == 'Company' || 
                        $edit_fields[$x] == 'Course' || $edit_fields[$x] == 'Year' || 
                        $edit_fields[$x] == 'Month'){
                        if($student_fields[$x] == null){
                            $prev = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Program'){
                                $prev = program::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Company'){
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
                            if($edit_fields[$x] == 'Program'){
                                $new = program::where('id', $request_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Company'){
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

        if($request->hasFile('t_picture')){
            if($student->picture != 'avatar5.png'){
                Storage::delete('public/img/student/'.$student->picture);
            }
            $encryption = sha1(date('jSFY').time().$request->t_picture->getClientOriginalName());
            $filename = $encryption.'.jpg';

            $target = storage_path().'/storage/img/student/'.$filename;
            Image::make($request->file('t_picture'))->save($target, 60, 'jpg');

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

    public function save_intern_student(Request $request){
        if($request->hasFile('t_picture')){
            $fileextension = $request->t_picture->getClientOriginalExtension();

            if($fileextension != 'jpg' && $fileextension != 'png' && $fileextension != 'jpeg' && $fileextension != 'JPG'){
                return false;
            }
        }
        
        $add_edit = $request->i_add_edit;

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
            $edit_fields = ['First Name', 'Middle Name', 'Last Name', 'Branch', 'University',
                'Benefactor', 'Contact #', 'Gender', 'Birth Date', 'Civil Status', 'Course', 
                'Email', 'Address', 'Referred By', 'Sign Up Date', 'Medical Date', 'Completion Date',
                'Year', 'Month', 'Remarks'];

            $student_fields = [$student->fname, $student->mname, $student->lname, $student->branch_id,
                $student->university_id, $student->benefactor_id, $student->contact, $student->gender, $student->birthdate,
                $student->civil_status, $student->course_id, $student->email, $student->address, $student->referral_id, 
                $student->date_of_signup, $student->date_of_medical, $student->date_of_completion,
                $student->departure_year_id, $student->departure_month_id, $student->remarks];

            $request_fields = [$request->i_fname, $request->i_mname, $request->i_lname, $request->i_branch,
                $request->i_university, $request->i_benefactor, $request->i_contact, $request->i_gender, $request->i_birthdate,
                $request->i_civil, $request->i_course, $request->i_email, $request->i_address, $request->i_referral, 
                $request->i_sign_up, $request->i_medical, $request->i_completion,
                $request->i_year, $request->i_month, $request->i_remarks];
        }

        $student->fname = $request->i_fname;
        $student->mname = $request->i_mname;
        $student->lname = $request->i_lname;
        $student->branch_id = $request->i_branch;

        $program = program::where('name', 'Internship')->first();
        $student->program_id = $program->id;

        $student->university_id = $request->i_university;
        $student->benefactor_id = $request->i_benefactor;
        $student->contact = $request->i_contact;
        $student->gender = $request->i_gender;
        $student->birthdate = Carbon::parse($request->i_birthdate);
        $student->civil_status = $request->i_civil;
        $student->course_id = $request->i_course;
        $student->email = $request->i_email;
        $student->address = $request->i_address;
        $student->referral_id = $request->i_referral;
        $student->date_of_signup = $request->i_sign_up;
        $student->date_of_medical = $request->i_medical ? Carbon::parse($request->i_medical) : null;
        $student->date_of_completion = $request->i_completion ? Carbon::parse($request->i_completion) : null;
        $student->departure_year_id = $request->i_year;
        $student->departure_month_id = $request->i_month;
        $student->remarks = $request->i_remarks;
        $student->referral_id = $request->i_referral;
        $student->branch_id = $request->i_branch;
        $student->save();

        // ADD HISTORY -- START

        if(isset($added_by)){
            $add_history = new student_add_history;
            $add_history->stud_id = $student->id;
            $add_history->type = 'Intern';
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
                    if($edit_fields[$x] == 'Branch' || $edit_fields[$x] == 'University' || $edit_fields[$x] == 'Benefactor' || 
                        $edit_fields[$x] == 'Course' || $edit_fields[$x] == 'Referred By' ||
                        $edit_fields[$x] == 'Year' || $edit_fields[$x] == 'Month'){
                        if($student_fields[$x] == null){
                            $prev = 'N/A';
                        }else{
                            if($edit_fields[$x] == 'Branch'){
                                $prev = branch::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'University'){
                                $prev = university::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Benefactor'){
                                $prev = benefactor::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $prev = course::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Referred By'){
                                $prev = employee::where('id', $student_fields[$x])->withTrashed()->pluck('fname');
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
                            if($edit_fields[$x] == 'Branch'){
                                $new = branch::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'University'){
                                $new = university::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Benefactor'){
                                $new = benefactor::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Course'){
                                $new = course::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Referred By'){
                                $new = employee::where('id', $student_fields[$x])->withTrashed()->pluck('fname');
                            }
                            else if($edit_fields[$x] == 'Year'){
                                $new = departure_year::where('id', $student_fields[$x])->pluck('name');
                            }
                            else if($edit_fields[$x] == 'Month'){
                                $new = departure_month::where('id', $student_fields[$x])->pluck('name');
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

        if($request->hasFile('i_picture')){
            if($student->picture != 'avatar5.png'){
                Storage::delete('public/img/student/'.$student->picture);
            }
            $encryption = sha1(date('jSFY').time().$request->picture->getClientOriginalName());
            $filename = $encryption.'.jpg';

            $target = storage_path().'/storage/img/student/'.$filename;
            Image::make($request->file('i_picture'))->save($target, 60, 'jpg');

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

    public function get_student(Request $request){
        $id = $request->id;
        $student = student::with('program', 'school', 'benefactor', 'company', 'university', 'referral', 'branch', 'course', 'departure_year', 'departure_month')->find($id);
        
        return $student;
    }

    public function delete_student(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

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
        
        if(!StudentHigherPermission()){
            $student = student::with('program', 'referral', 'branch', 'course', 'departure_year', 'departure_month')->find($id);
            //$student->contact = '-';
        }else{
            $student = student::with('program', 'school', 'benefactor', 'company', 'referral', 'branch', 'course', 'departure_year', 'departure_month', 'emergency')->find($id);
        }

        $birth = new Carbon($student->birthdate);
        $student->age = $birth->diffInYears(Carbon::now());

        return $student;
    }

    public function get_student_info(Request $request){
        $id = $request->id;
        
        $student = student::find($id);
        return $student;
    }

    public function get_student_emergency(Request $request){
        return student_emergency::find($request->id);
    }

    public function get_student_emp_history(Request $request){
        return student_emp_history::find($request->id);
    }

    public function get_student_education(Request $request){
        return student_educational_background::find($request->id);
    }

    public function view_student_emergency(Request $request){
        $id = $request->id;

        $emergency = student_emergency::where('stud_id', $id)->get();

        return Datatables::of($emergency)
        ->addColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            $html = '';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_emergency" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            
            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_emergency" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }

            return $html;
        })
        ->make(true);
    }

    public function view_student_employment(Request $request){
        $id = $request->id;

        $employment = student_emp_history::where('stud_id', $id)->get();

        return Datatables::of($employment)
        ->addColumn('action', function($data){
            $html = '';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_emp_history" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            
            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_emp_history" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }

            return $html;
        })
        ->make(true);
    }

    public function view_student_education(Request $request){
        $id = $request->id;

        $education = student_educational_background::where('stud_id', $id)->get();

        return Datatables::of($education)
        ->addColumn('action', function($data){
            $html = '';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_education" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            
            if(canAccessAll()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_education" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            }

            return $html;
        })
        ->make(true);
    }

    public function save_student_emergency(Request $request){
        if($request->e_add_edit == 'add'){
            $emergency = new student_emergency;
        }else{
            $emergency = student_emergency::find($request->e_id);
        }

        $emergency->stud_id = $request->e_stud_id;
        $emergency->fname = $request->e_fname;
        $emergency->mname = $request->e_mname;
        $emergency->lname = $request->e_lname;
        $emergency->relationship = $request->e_relationship;
        $emergency->contact = $request->e_contact;
        $emergency->save();

        return $request->e_stud_id;
    }

    public function save_student_emp_history(Request $request){
        if($request->eh_add_edit == 'add'){
            $history = new student_emp_history;
        }else{
            $history = student_emp_history::find($request->eh_id);
        }

        $history->stud_id = $request->eh_stud_id;
        $history->name = $request->eh_company;
        $history->position = $request->eh_position;
        $history->start = $request->eh_started;
        $history->finished = $request->eh_finished;
        $history->save();

        return $request->eh_stud_id;
    }

    public function save_student_education(Request $request){
        if($request->eb_add_edit == 'add'){
            $education = new student_educational_background;
        }else{
            $education = student_educational_background::find($request->eb_id);
        }

        $education->stud_id = $request->eb_stud_id;
        $education->name = $request->eb_school;
        $education->start = $request->eb_start;
        $education->end = $request->eb_end;
        $education->level = $request->eb_level;
        $education->course = $request->eb_course;
        $education->save();

        return $request->eb_stud_id;
    }

    public function delete_student_emergency(Request $request){
        $emergency = student_emergency::find($request->id);
        $stud_id = $emergency->stud_id;
        $emergency->delete();

        return $stud_id;
    }

    public function delete_student_emp_history(Request $request){
        $history = student_emp_history::find($request->id);
        $stud_id = $history->stud_id;
        $history->delete();

        return $stud_id;
    }

    public function delete_student_education(Request $request){
        $education = student_educational_background::find($request->id);
        $stud_id = $education->stud_id;
        $education->delete();

        return $stud_id;
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
            ->where('name', '<>', 'SSW (Hospitality)')->where('name', '<>', 'TITP')->where('name', '<>', 'TITP (Careworker)')
            ->get()->toArray();

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

    public function program_titp(Request $request){
        $program = program::where('name', 'LIKE', '%'.$request->name.'%')
            ->where('name', '=', 'TITP')->orWhere('name', '=', 'TITP (Careworker)')->get()->toArray();

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

    public function university_all(Request $request){
        $university = university::where('name', 'LIKE', '%'.$request->name.'%')->get()->toArray();

        $array = [];
        foreach ($university as $key => $value){
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
