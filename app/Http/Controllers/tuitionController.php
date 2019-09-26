<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sec_bond;
use App\tf_name;
use App\tf_payment;
use App\tf_projected;
use App\tf_student;
use App\program;
use App\student;
use App\class_students;
use Yajra\Datatables\Datatables;

class tuitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $student = tf_student::with('student.program')->get();

        return view('pages.tuition', compact('student'));
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

    public function view_tf_student(){
        $tf_student = tf_student::with('student.program')->get();

        return Datatables::of($tf_student)
        ->addColumn('name', function($data){
            return $data->student->lname . ', ' . $data->student->fname . ' ' . $data->student->mname;
        })
        ->editColumn('balance', function($data){
            $tf_payment = tf_payment::where('tf_stud_id', $data->id)->sum('amount');

            return $data->balance - $tf_payment;
        })
        ->addColumn('sec_bond', function($data){
            return sec_bond::where('tf_stud_id', $data->id)->sum('amount');
        })
        ->addColumn('class', function($data){
            $class_students = class_students::with('student', 'current_class.sensei')->where('stud_id', $data->stud_id)
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
        ->addColumn('action', function($data){
            return '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Student" class="btn btn-warning btn-sm view_student_tuition" id="'.$data->id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>&nbsp;';
        })
        ->make(true);
    }

    public function view_tuition_sec(Request $request){
        $current_tab = $request->current_tab;

        if($current_tab == 'Tuition Fee Payment History'){
            $tf_sb = tf_payment::with('student.student.program')->get();
            $edit_button = 'edit_tf_payment';
        }
        else if($current_tab == 'Security Bond Payment History'){
            $tf_sb = sec_bond::with('student.student.program')->get();
            $edit_button = 'edit_sb_payment';
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
        ->addColumn('action', function($data) use($edit_button){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-xs '.$edit_button.'" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';

            return $html;
        })
        ->make(true);
    }

    public function view_tuition_fee(Request $request){
        $id = $request->id;
        
        $tf_payment = tf_payment::where('tf_stud_id', $id)->get();

        return Datatables::of($tf_payment)
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-xs edit_tf_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';

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

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-xs edit_sb_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';

            return $html;
        })
        ->make(true);
    }

    public function get_tf_projected(Request $request){
        $tf_name_list = [1, 2, 3, 4, 5, 6];
        $tf_projected = tf_projected::where('program_id', $request->id)->get();

        $output = array(
            'tf_name_list' => $tf_name_list,
            'tf_projected' => $tf_projected,
        );

        echo json_encode($output);
    }

    public function get_student_tuition(Request $request){
        $tf_student = tf_student::with('student')->find($request->id);
        
        $tf_payment = tf_payment::where('tf_stud_id', $tf_student->id)->sum('amount');
        $tf_payment = $tf_student->balance - $tf_payment;

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

        $balance = tf_projected::where('program_id', $student->program_id)->sum('amount');
        
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
}