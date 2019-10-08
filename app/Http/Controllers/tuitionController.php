<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\sec_bond;
use App\tf_name;
use App\tf_payment;
use App\tf_projected;
use App\program;
use App\branch;
use App\student;
use App\class_students;
use App\class_settings;
use App\departure_year;
use App\departure_month;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Redirect;

class tuitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $class_settings = class_settings::with('sensei')->orderBy('start_date', 'desc')->get();
        $program = program::all();
        $branch = branch::all();
        $departure_year = departure_year::all();
        $departure_month = departure_month::all();

        return view('pages.tuition', compact('student', 'class_settings', 'program',
            'branch', 'departure_year', 'departure_month'));
    }

    public function view_tf_program(){
        $program = program::all();

        return Datatables::of($program)
        ->addColumn('total', function($data){
            $tf_projected = tf_projected::where('program_id', $data->id)->sum('amount');

            return $tf_projected;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Projected Expense" class="btn btn-success btn-sm projection" id="'.$data->id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>&nbsp;';

            return $html;
        })
        ->make(true);
    }

    public function view_tf_student(Request $request){
        $class = $request->class_select;
        $program = $request->program_select;
        $branch = $request->branch_select;
        $departure_year = $request->departure_year_select;
        $departure_month = $request->departure_month_select;
        $current_class = [];

        if($class != 'All'){
            $student_group = student::pluck('id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('stud_id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class_students->class_settings_id == $class){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }

        $student = student::with('program', 'branch')
        ->when($class != 'All', function($query) use($current_class){
            $query->whereIn('id', $current_class);
        })
        ->when($program != 'All', function($query) use($program){
            $query->where('program_id', $program);
        })
        ->when($branch != 'All', function($query) use($branch){
            $query->where('branch_id', $branch);
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->get();

        return Datatables::of($student)
        ->addColumn('name', function($data){
            return $data->lname . ', ' . $data->fname . ' ' . $data->mname;
        })
        ->editColumn('balance', function($data){
        })
        ->addColumn('sec_bond', function($data){
        })
        ->addColumn('class', function($data){
            $class_students = class_students::with('student', 'current_class.sensei')->where('stud_id', $data->id)
                ->orderBy('id', 'desc')->first();

            $html = '';

            if($class_students){
                if($class_students->end_date && $class_students->student->status != 'Back Out'){
                    $html .= 'Complete ';
                }
                else if($class_students->end_date && $class_students->student->status == 'Back Out'){
                    $html .= 'Back Out ';
                }
                else{
                    $html .= 'Active ';
                }

                $html .= '(' . $class_students->current_class->sensei->fname . ') | ' . $class_students->current_class->start_date . ' ~ ' 
                    . (($class_students->current_class->end_date) ? $class_students->current_class->end_date : 'TBD');

                return $html;
            }else{
                return 'N/A';
            }
        })
        ->addColumn('departure', function($data){
            if($data->program){
                if($data->program->name == 'Language Only' || 
                    $data->program->name == 'SSW (Careworker)' ||
                    $data->program->name == 'SSW (Hospitality)'){
                    return 'N/A';
                }
            }

            return $data->departure_year->name . ' ' . $data->departure_month->name;
        })
        ->addColumn('action', function($data){
            return '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Student" class="btn btn-warning btn-sm view_student_tuition" id="'.$data->id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>&nbsp;';
        })
        ->make(true);
    }

    public function view_tuition_sec(Request $request){
        $current_tab = $request->current_tab;
        $class = $request->class_select;
        $program = $request->program_select;
        $branch = $request->branch_select;
        $departure_year = $request->departure_year_select;
        $departure_month = $request->departure_month_select;
        $current_class = [];

        if($class != 'All'){
            $student_group = tf_student::pluck('stud_id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('stud_id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class_students->class_settings_id == $class){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }
        
        $tf_student = tf_student::with('student.program', 'student.branch')
        ->when($class != 'All', function($query) use($current_class){
            $query->whereIn('stud_id', $current_class);
        })
        ->when($program != 'All', function($query) use($program){
            $query->whereHas('student', function($query) use($program){
                $query->where('program_id', $program);
            });
        })
        ->when($branch != 'All', function($query) use($branch){
            $query->whereHas('student', function($query) use($branch){
                $query->where('branch_id', $branch);
            });
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->whereHas('student', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            });
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->whereHas('student', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            });
        })
        ->pluck('id');

        if($current_tab == 'Tuition Fee Payment History'){
            $tf_sb = tf_payment::with('student.student.program', 'student.student.branch',
            'student.student.departure_year', 'student.student.departure_month')
            ->when(!empty($tf_student), function($query) use($tf_student){
                $query->whereIn('tf_stud_id', $tf_student);
            })->get();
            $edit_button = 'edit_tf_payment';
            $delete_button = 'delete_tf_payment';
        }
        else if($current_tab == 'Security Bond Payment History'){
            $tf_sb = sec_bond::with('student.student.program', 'student.student.branch')->when(!empty($tf_student), function($query) use($tf_student){
                $query->whereIn('tf_stud_id', $tf_student);
            })->get();
            $edit_button = 'edit_sb_payment';
            $delete_button = 'delete_sb_payment';
        }

        return Datatables::of($tf_sb)
        ->addColumn('name', function($data){
            return $data->student->student->lname . ', ' . $data->student->student->fname . ' ' . $data->student->student->mname;
        })
        ->addColumn('class', function($data){
            $class_students = class_students::with('student', 'current_class.sensei')->where('stud_id', $data->student->stud_id)
                ->orderBy('id', 'desc')->first();

            $html = '';

            if($class_students){
                if($class_students->end_date && $class_students->student->status != 'Back Out'){
                    $html .= 'Complete ';
                }
                else if($class_students->end_date && $class_students->student->status == 'Back Out'){
                    $html .= 'Back Out ';
                }
                else{
                    $html .= 'Active ';
                }

                $html .= '(' . $class_students->current_class->sensei->fname . ') | ' . $class_students->current_class->start_date . ' ~ ' 
                    . (($class_students->current_class->end_date) ? $class_students->current_class->end_date : 'TBD');

                return $html;
            }else{
                return 'N/A';
            }
        })
        ->editColumn('remarks', function($data){
            $html = $data->remarks;
            if($data->sign_up && $data->sign_up == 1){
                $html .= ' | Sign-Up Fee';
            }
            return $html;
        })
        ->addColumn('action', function($data) use($edit_button, $delete_button){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Student" class="btn btn-warning btn-xs view_student_tuition" id="'.$data->tf_stud_id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs '.$edit_button.'" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs '.$delete_button.'" id="'.$data->id.'"><i class="fa fa-trash"></i></button>&nbsp;';

            return $html;
        })
        ->make(true);
    }

    public function view_tuition_fee(Request $request){
        $id = $request->id;
        
        $tf_payment = tf_payment::where('tf_stud_id', $id)->get();

        return Datatables::of($tf_payment)
        ->editColumn('remarks', function($data){
            $html = $data->remarks;
            if($data->sign_up && $data->sign_up == 1){
                $html .= ' | Sign-Up Fee';
            }
            return $html;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_tf_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_tf_payment" id="'.$data->id.'"><i class="fa fa-trash"></i></button>&nbsp;';

            return $html;
        })
        ->make(true);
    }

    public function view_sec_bond(Request $request){
        $id = $request->id;
        
        $sec_bond = sec_bond::where('tf_stud_id', $id)->get();

        return Datatables::of($sec_bond)
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_sb_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_sb_payment" id="'.$data->id.'"><i class="fa fa-trash"></i></button>&nbsp;';

            return $html;
        })
        ->make(true);
    }

    public function view_tf_breakdown(Request $request){
        $class_select = $request->class_select;
        $program_select = $request->program_select;
        $branch_select = $request->branch_select;
        $departure_year_select = $request->departure_year_select;
        $departure_month_select = $request->departure_month_select;
        $installment = 0;

        $current_class = [];

        if($class_select != 'All'){
            $student_group = tf_student::pluck('stud_id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('stud_id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class_students->class_settings_id == $class_select){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }

        $tf_student = tf_student::with('student.program', 'payment')
        ->when($class_select != 'All', function($query) use($current_class){
            $query->whereIn('stud_id', $current_class);
        })
        ->when($program_select != 'All', function($query) use($program_select){
            $query->whereHas('student', function($query) use($program_select){
                $query->where('program_id', $program_select);
            });
        })
        ->when($branch_select != 'All', function($query) use($branch_select){
            $query->whereHas('student', function($query) use($branch_select){
                $query->where('branch_id', $branch_select);
            });
        })
        ->when($departure_year_select != 'All', function($query) use($departure_year_select){
            $query->whereHas('student', function($query) use($departure_year_select){
                $query->where('departure_year_id', $departure_year_select);
            });
        })
        ->when($departure_month_select != 'All', function($query) use($departure_month_select){
            $query->whereHas('student', function($query) use($departure_month_select){
                $query->where('departure_month_id', $departure_month_select);
            });
        })->get();

        foreach($tf_student as $ts){
            $temp = tf_payment::where('tf_stud_id', $ts->id)->where('sign_up', 0)->count();
            if($temp > $installment){
                $installment = $temp;
            }
            $ts->prof_fee = tf_payment::where('tf_stud_id', $ts->id)->where('sign_up', 1)->sum('amount');
            if($ts->prof_fee != 0){
                $date_temp = tf_payment::where('tf_stud_id', $ts->id)->orderBy('date', 'desc')->where('sign_up', 1)->first();
                $ts->prof_fee_date = $date_temp->date;
            }
            else{
                $ts->prof_fee_date = '';
            }
            $ts->total_payment = tf_payment::where('tf_stud_id', $ts->id)->where('sign_up', 0)->sum('amount');
            $ts->remaining_bal = $ts->balance - $ts->total_payment;
        }

        //For Footer
        $tf_student_footer = $tf_student->pluck('id');

        $footer = new Collection;
        $footer->put('sign_up', tf_payment::whereIn('tf_stud_id', $tf_student_footer)->where('sign_up', 1)->sum('amount'));
        $footer->put('total_tuition', tf_student::whereIn('id', $tf_student_footer)->sum('balance'));
        $footer->put('total_payment', tf_payment::whereIn('tf_stud_id', $tf_student_footer)->where('sign_up', 0)->sum('amount'));

        $footer_installment = [];

        for($x = 0; $x < $installment; $x++){
            $temp_installment = 0;
            foreach($tf_student as $ts){
                if(!empty($ts->payment[$x])){
                    $temp_installment += $ts->payment[$x]->amount;
                }
            }
            array_push($footer_installment, $temp_installment);
        }
        
        $footer->put('installment', $footer_installment);
        $footer->put('balance', $footer['total_tuition'] - $footer['total_payment']);

        $output = array(
            'tf_student' => $tf_student,
            'installment' => $installment,
            'footer' => $footer
        );

        echo json_encode($output);
    }

    public function view_summary(Request $request){
        $program_select = $request->program_select;
        $branch_select = $request->branch_select;
        $departure_year_select = $request->departure_year_select;
        $departure_month_select = $request->departure_month_select;
        $current_class = [];

        $tf_student = tf_student::with('student.program', 'student.school', 'payment')
        ->when($program_select != 'All', function($query) use($program_select){
            $query->whereHas('student', function($query) use($program_select){
                $query->where('program_id', $program_select);
            });
        })
        ->when($branch_select != 'All', function($query) use($branch_select){
            $query->whereHas('student', function($query) use($branch_select){
                $query->where('branch_id', $branch_select);
            });
        })
        ->when($departure_year_select != 'All', function($query) use($departure_year_select){
            $query->whereHas('student', function($query) use($departure_year_select){
                $query->where('departure_year_id', $departure_year_select);
            });
        })
        ->when($departure_month_select != 'All', function($query) use($departure_month_select){
            $query->whereHas('student', function($query) use($departure_month_select){
                $query->where('departure_month_id', $departure_month_select);
            });
        })->get();

        foreach($tf_student as $ts){
            $ts->total_payment = tf_payment::where('tf_stud_id', $ts->id)->sum('amount');
            $ts->sec_bond = sec_bond::where('tf_stud_id', $ts->id)->sum('amount');
        }

        return $tf_student;
    }

    public function get_tf_projected(Request $request){
        $tf_name_list = [1, 2, 3, 4, 5, 6, 7, 8];
        $tf_projected = tf_projected::where('program_id', $request->id)->get();

        $output = array(
            'tf_name_list' => $tf_name_list,
            'tf_projected' => $tf_projected,
        );

        echo json_encode($output);
    }

    public function get_student_tuition(Request $request){
        $tf_student = tf_student::with('student')->find($request->id);
        
        $tf_payment = tf_payment::where('sign_up', 0)->where('tf_stud_id', $tf_student->id)->sum('amount');
        $tf_payment = $tf_student->balance - $tf_payment;

        $tf_sign_up = tf_payment::where('sign_up', 1)->where('tf_stud_id', $tf_student->id)->sum('amount');

        $sec_bond = sec_bond::where('tf_stud_id', $tf_student->id)->sum('amount');

        $class_students = class_students::with('student', 'current_class.sensei')->where('stud_id', $tf_student->stud_id)
                ->orderBy('id', 'desc')->first();

        $html = '';

        if($class_students){
            if($class_students->end_date && $class_students->student->status != 'Back Out'){
                $html .= 'Complete ';
            }
            else if($class_students->end_date && $class_students->student->status == 'Back Out'){
                $html .= 'Back Out ';
            }
            else{
                $html .= 'Active ';
            }

            $html .= '(' . $class_students->current_class->sensei->fname . ') | ' . $class_students->current_class->start_date . ' ~ ' 
            . (($class_students->current_class->end_date) ? $class_students->current_class->end_date : 'TBD');
        }else{
            $html = 'N/A';
        }

        $output = array(
            'tf_student' => $tf_student,
            'tf_payment' => $tf_payment,
            'tf_sign_up' => $tf_sign_up,
            'sec_bond' => $sec_bond,
            'class' => $html
        );

        echo json_encode($output);
    }

    public function get_tf_sb_payment(Request $request){
        $id = $request->id;
        $p_type = $request->p_type;

        if($p_type == 'tuition'){
            $tf_sb_payment = tf_payment::with('student')->find($id);
        }
        else if($p_type == 'sec_bond'){
            $tf_sb_payment = sec_bond::with('student')->find($id);
        }

        return $tf_sb_payment;
    }

    public function save_projection(Request $request){
        $prog_id = $request->prog_id;
        $proj_name_id = $request->proj_name_id;
        //1=SignUp,2=VisaProcessing,3=Language,4=Documentation,5=Selection,6=PDOS
        $proj_amount = $request->proj_amount;
        $proj_date = $request->proj_date;
        $proj_remarks = $request->proj_remarks;
        
        $tf_name_count = tf_name::count();
        $tf_projected = tf_projected::where('program_id', $prog_id)->get();

        if($tf_projected->count() != 0){
            for($x = 0; $x < $tf_name_count; $x++){
                $tf_project_temp = $tf_projected->where('tf_name_id', $proj_name_id[$x])->first();
                if(!$tf_project_temp || $tf_project_temp->count() == 0){
                    $tf_project_temp = new tf_projected;
                }
                $tf_project_temp->tf_name_id = $request->proj_name_id[$x];
                $tf_project_temp->program_id = $request->prog_id;
                $tf_project_temp->amount = $request->proj_amount[$x];
                $tf_project_temp->date_of_payment = $request->proj_date[$x];
                $tf_project_temp->remarks = $request->proj_remarks[$x];
                $tf_project_temp->save();
            }
        }
        else{
            for($x = 0; $x < $tf_name_count; $x++){
                $tf_project_temp = new tf_projected;
                $tf_project_temp->tf_name_id = $request->proj_name_id[$x];
                $tf_project_temp->program_id = $request->prog_id;
                $tf_project_temp->amount = $request->proj_amount[$x];
                $tf_project_temp->date_of_payment = $request->proj_date[$x];
                $tf_project_temp->remarks = $request->proj_remarks[$x];
                $tf_project_temp->save();
            }
        }
    }

    public function save_tf_student(Request $request){
        $tf_student = new tf_student;
        $tf_student->stud_id = $request->student;
        $tf_student->balance = $request->balance;
        $tf_student->save();
    }

    public function save_tf_sb_payment(Request $request){
        $id = $request->p_id;

        if($request->p_type == 'tuition'){
            $tf_sb = ($request->add_edit == 'add') ? new tf_payment : tf_payment::find($id);
            $tf_sb->sign_up = $request->sign_up;
        }
        else if($request->p_type == 'sec_bond'){
            $tf_sb = ($request->add_edit == 'add') ? new sec_bond : sec_bond::find($id);
        }

        if($request->p_student){
            $tf_sb->tf_stud_id = $request->p_student;
        }
        $tf_sb->amount = $request->p_amount;
        $tf_sb->date = $request->date;
        $tf_sb->remarks = $request->remarks;
        $tf_sb->save();
    }

    public function save_initial_balance(Request $request){
        $tf_student = tf_student::find($request->i_id);

        $tf_student->balance = $request->init_balance;
        $tf_student->save();
    }

    public function t_get_student(Request $request){
        $tf_student = tf_student::pluck('stud_id');

        $student = student::with('program')->where('fname', 'LIKE', '%'.$request->name.'%')
        ->orWhere('lname', 'LIKE', '%'.$request->name.'%')->get();

        $student = $student->whereNotIn('id', $tf_student);

        $array = [];
        foreach ($student as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['fname'].' '.$value['lname'].' ('.$value['program']['name'].')'
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function get_tf_student(Request $request){
        $name = $request->name;

        $tf_student = tf_student::with('student', 'student.program')
        ->whereHas('student', function($query) use($name){
            $query->where('fname', 'LIKE', '%'.$name.'%')
            ->orWhere('lname', 'LIKE', '%'.$name.'%');
        })->get();

        $array = [];
        foreach ($tf_student as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['student']['fname'].' '.$value['student']['lname'].' ('.$value['student']['program']['name'].')'
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function get_balance_class(Request $request){
        $id = $request->id;

        $student = student::find($id);

        $balance = tf_projected::where('tf_name_id', '<>', 1)->where('program_id', $student->program_id)->sum('amount');
        
        $class = class_students::with('student', 'current_class.sensei')->where('stud_id', $id)
        ->orderBy('id', 'desc')->first();

        if($class){
            $html = '';
            if($class->end_date && $class->student->status != 'Back Out'){
                $html .= 'Complete ';
            }
            else if($class->end_date && $class->student->status == 'Back Out'){
                $html .= 'Back Out ';
            }
            else{
                $html .= 'Active ';
            }

            $class = $html . '(' . $class->current_class->sensei->fname . ')';
        }else{
            $class = 'N/A';
        }

        
        $output = array(
            'balance' => $balance,
            'class' => $class
        );

        return json_encode($output);
    }

    public function get_initial_balance(Request $request){
        return tf_student::with('student')->find($request->id);
    }

    public function delete_tf_payment(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $tf_payment = tf_payment::find($request->id);
        $tf_payment->delete();
        return $tf_payment->tf_stud_id;
    }

    public function delete_sb_payment(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $sec_bond = sec_bond::find($request->id);
        $sec_bond->delete();
        return $sec_bond->tf_stud_id;
    }
}