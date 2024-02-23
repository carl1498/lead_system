<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use App\Traits\LogsTraits;
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
use App\soa;
use App\soa_fees;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Redirect;

class tuitionController extends Controller
{
    use LogsTraits;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $this->page_access_log(Auth::user()->emp_id, 'Tuition', 'Visit');

        $student = student::with('program')->get();
        $tf_name = tf_name::all();
        $class_settings = class_settings::with('sensei')->orderBy('start_date', 'desc')->get();
        $program = program::all();
        $branch = branch::all();
        $departure_year = departure_year::all();
        $departure_month = departure_month::all();
        $batch = student::whereNotNull('batch')->groupBy('batch')->pluck('batch');

        return view('pages.tuition', compact('student', 'tf_name', 'class_settings', 'program',
            'branch', 'departure_year', 'departure_month', 'batch'));
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

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Projected Expense" class="btn btn-success btn-sm projection" id="'.$data->id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>';

            return $html;
        })
        ->make(true);
    }

    public function view_tf_student(Request $request){
        $class = $request->class_filter;
        $program = $request->program_filter;
        $branch = $request->branch_filter;
        $departure_year = $request->departure_year_filter;
        $departure_month = $request->departure_month_filter;
        $current_class = [];

        if($class != 'All'){
            $student_group = student::pluck('id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('stud_id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class == 'No Class'){
                        array_push($current_class, $class_students->stud_id);
                    }
                    else if($class_students->class_settings_id == $class){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }

        $student = student::with('program', 'branch')->limit(1)
        ->when($class != 'All' && $class != 'No Class', function($query) use($current_class){
            $query->whereIn('id', $current_class);
        })
        ->when($class == 'No Class', function($query) use($current_class){
            $query->whereNotIn('id', $current_class);
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
            $tf_projected = tf_projected::where('program_id', $data->program_id)->sum('amount');
            $tf_payment = tf_payment::where('stud_id', $data->id)->sum('amount');
            return $tf_projected - $tf_payment;
        })
        ->addColumn('sec_bond', function($data){
            return sec_bond::where('stud_id', $data->id)->sum('amount');
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
                if(in_array($data->program->name, ['SSW (Careworker)', 'SSW (Hospitality)', 'SSW (Food Processing)', 'SSW (Construction)', 'Language Only'])){
                    return 'N/A';
                }
            }

            return $data->departure_year->name . ' ' . $data->departure_month->name;
        })
        ->addColumn('action', function($data){
            return '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Student" class="btn btn-warning btn-sm view_tf_student_modal" id="'.$data->id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>';
        })
        ->make(true);
    }
    
    public function view_tf_student_modal(Request $request){
        $id = $request->id;

        $student = student::find($id);

        $tf_projected = tf_projected::where('program_id', $student->program_id)->orderBy('tf_name_id', 'asc')->get();
        $tp_total = tf_projected::where('program_id', $student->program_id)->sum('amount');

        $sb_total = sec_bond::where('stud_id', $id)->sum('amount');
        
        $class_students = class_students::with('student', 'current_class.sensei')->where('stud_id', $student->id)
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

            $class_students = $html;
        }else{
            $class_students = 'N/A';
        }

        $tf = [];
        for($x = 0; $x < 8; $x++){
            $tf[$x] = tf_payment::where('stud_id', $student->id)->where('tf_name_id', $x+1)->sum('amount');
        }
        $tf[8] = tf_payment::where('stud_id', $student->id)->sum('amount');

        $output = array(
            'student' => $student,
            'tf_projected' => $tf_projected,
            'class_students' => $class_students,
            'tf' => $tf,
            'sb_total' => $sb_total,
            'tp_total' => $tp_total,
        );

        echo json_encode($output);
    }

    public function view_tf_payment(Request $request){
        $class = $request->class_filter;
        $program = $request->program_filter;
        $branch = $request->branch_filter;
        $departure_year = $request->departure_year_filter;
        $departure_month = $request->departure_month_filter;
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
        
        $tf_payment = tf_payment::with('student.program', 'student.branch', 'tf_name')
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
        ->get();

        return Datatables::of($tf_payment)
        ->addColumn('name', function($data){
            return $data->student->lname . ', ' . $data->student->fname . ' ' . (($data->student->mname) ? $data->student->mname : '');
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Student" class="btn btn-warning btn-xs view_tf_student_modal" id="'.$data->id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit Payment" class="btn btn-info btn-xs edit_tf_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete Payment" class="btn btn-danger btn-xs delete_tf_payment" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';

            return $html;
        })->make(true);
    }

    public function view_sec_bond(Request $request){
        $class = $request->class_filter;
        $program = $request->program_filter;
        $branch = $request->branch_filter;
        $departure_year = $request->departure_year_filter;
        $departure_month = $request->departure_month_filter;
        $current_class = [];
        
        if($class != 'All'){
            $student_group = student::pluck('stud_id');
            
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
        
        $sec_bond = sec_bond::with('student.program', 'student.branch')
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
        ->get();

        return Datatables::of($sec_bond)
        ->addColumn('name', function($data){
            return $data->student->lname . ', ' . $data->student->fname . ' ' . (($data->student->mname) ? $data->student->mname : '');
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Student" class="btn btn-warning btn-xs view_tf_student_modal" id="'.$data->id.'"><i class="fa fa-list-alt" style="font-size: 15px;"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit Payment" class="btn btn-info btn-xs edit_sb_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete Payment" class="btn btn-danger btn-xs delete_sb_payment" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';

            return $html;
        })->make(true);
    }

    public function view_tf_modal(Request $request){
        $id = $request->id;

        $tf_payment = tf_payment::with('tf_name')->where('stud_id', $id)->get();

        return Datatables::of($tf_payment)
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit Payment" class="btn btn-info btn-xs edit_tf_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete Payment" class="btn btn-danger btn-xs delete_tf_payment" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';

            return $html;
        })->make(true);
    }

    public function view_sb_modal(Request $request){
        $id = $request->id;

        $sec_bond = sec_bond::where('stud_id', $id)->get();

        return Datatables::of($sec_bond)
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit Payment" class="btn btn-info btn-xs edit_sb_payment" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete Payment" class="btn btn-danger btn-xs delete_sb_payment" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';

            return $html;
        })->make(true);
    }

    public function view_tf_breakdown(Request $request){
        $class_filter = $request->class_filter;
        $program_filter = $request->program_filter;
        $branch_filter = $request->branch_filter;
        $departure_year_filter = $request->departure_year_filter;
        $departure_month_filter = $request->departure_month_filter;
        $installment = 0;

        $current_class = [];

        if($class_filter != 'All'){
            $student_group = student::pluck('id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('stud_id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class_students->class_settings_id == $class_filter){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }

        $student = student::with('program', 'payment')
        ->when($class_filter != 'All', function($query) use($current_class){
            $query->whereIn('id', $current_class);
        })
        ->when($program_filter != 'All', function($query) use($program_filter){
            $query->where('program_id', $program_filter);
        })
        ->when($branch_filter != 'All', function($query) use($branch_filter){
            $query->where('branch_id', $branch_filter);
        })
        ->when($departure_year_filter != 'All', function($query) use($departure_year_filter){
            $query->where('departure_year_id', $departure_year_filter);
        })
        ->when($departure_month_filter != 'All', function($query) use($departure_month_filter){
            $query->where('departure_month_id', $departure_month_filter);
        })->get();

        $total_tuition = 0;

        foreach($student as $s){
            $temp = tf_payment::where('stud_id', $s->id)->where('tf_name_id', 3)->count();
            if($temp > $installment){
                $installment = $temp;
            }
            $s->prof_fee = tf_payment::where('stud_id', $s->id)->where('tf_name_id', 1)->sum('amount');
            if($s->prof_fee != 0){
                $s->prof_fee_date = tf_payment::where('stud_id', $s->id)->orderBy('date', 'desc')->where('tf_name_id', 1)->value('date');
            }
            else{
                $s->prof_fee_date = '';
            }
            $s->total_payment = tf_payment::where('stud_id', $s->id)->where('tf_name_id', 3)->sum('amount');
            $s->balance = tf_projected::where('program_id', $s->program_id)->where('tf_name_id', 3)->sum('amount');
            $s->remaining_bal = $s->balance - $s->total_payment;
            $total_tuition += $s->balance;
        }

        //For Footer
        $student_footer = $student->pluck('id');

        $footer = new Collection;
        $footer->put('sign_up', tf_payment::whereIn('stud_id', $student_footer)->where('tf_name_id', 1)->sum('amount'));
        $footer->put('total_tuition', $total_tuition);
        $footer->put('total_payment', tf_payment::whereIn('stud_id', $student_footer)->where('tf_name_id', 3)->sum('amount'));

        $footer_installment = [];

        for($x = 0; $x < $installment; $x++){
            $temp_installment = 0;
            foreach($student as $s){
                if(!empty($s->payment[$x])){
                    $temp_installment += $s->payment[$x]->amount;
                }
            }
            array_push($footer_installment, $temp_installment);
        }
        
        $footer->put('installment', $footer_installment);
        $footer->put('balance', $footer['total_tuition'] - $footer['total_payment']);

        $output = array(
            'student' => $student,
            'installment' => $installment,
            'footer' => $footer
        );

        echo json_encode($output);
    }

    public function view_summary(Request $request){
        $program_filter = $request->program_filter;
        $branch_filter = $request->branch_filter;
        $departure_year_filter = $request->departure_year_filter;
        $departure_month_filter = $request->departure_month_filter;
        $current_class = [];

        $student = student::with('program', 'school', 'payment')
        ->when($program_filter != 'All', function($query) use($program_filter){
            $query->where('program_id', $program_filter);
        })
        ->when($branch_filter != 'All', function($query) use($branch_filter){
            $query->where('branch_id', $branch_filter);
        })
        ->when($departure_year_filter != 'All', function($query) use($departure_year_filter){
            $query->where('departure_year_id', $departure_year_filter);
        })
        ->when($departure_month_filter != 'All', function($query) use($departure_month_filter){
            $query->where('departure_month_id', $departure_month_filter);
        })->get();

        foreach($student as $s){
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);

            $s->sec_bond = sec_bond::where('stud_id', $s->id)->sum('amount');
            $s->tf_su = ($tf_projected
            ->where(function($query){
                $query->where('tf_name_id', 1)->orWhere('tf_name_id', 3);
            })->sum('amount')) - 
            ($tp_temp
            ->where(function($query){
                $query->where('tf_name_id', 1)->orWhere('tf_name_id', 3);
            })->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->visa = ($tf_projected->where('tf_name_id', 2)->sum('amount')) - ($tp_temp->where('tf_name_id', 2)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->docu = ($tf_projected->where('tf_name_id', 4)->sum('amount')) - ($tp_temp->where('tf_name_id', 4)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->select = ($tf_projected->where('tf_name_id', 5)->sum('amount')) - ($tp_temp->where('tf_name_id', 5)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->pdos = ($tf_projected->where('tf_name_id', 6)->sum('amount')) - ($tp_temp->where('tf_name_id', 6)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->air = ($tf_projected->where('tf_name_id', 7)->sum('amount')) - ($tp_temp->where('tf_name_id', 7)->sum('amount'));
            $tf_projected = tf_projected::where('program_id', $s->program_id);$tp_temp = tf_payment::where('stud_id', $s->id);
            $s->dhl = ($tf_projected->where('tf_name_id', 8)->sum('amount')) - ($tp_temp->where('tf_name_id', 8)->sum('amount'));
        }

        return $student;
    }

    public function view_soa(Request $request){
        $class_filter = $request->class_filter;
        $program_filter = $request->program_filter;
        $batch_filter = $request->batch_filter;

        $current_class = [];

        if($class_filter != 'All'){
            $student_group = student::pluck('id');
            
            for($x = 0; $x < count($student_group); $x++){
                $class_students = class_students::where('stud_id', $student_group[$x])
                    ->orderBy('id', 'desc')->first();

                if(!empty($class_students)){
                    if($class_students->class_settings_id == $class_filter){
                        array_push($current_class, $class_students->stud_id);
                    }
                }
            }
        }

        $soa_id = soa::groupBy('stud_id')->pluck('stud_id');

        $student = student::with('program')->whereIn('id', $soa_id)
        ->when($class_filter != 'All', function($query) use($current_class){
            $query->whereIn('id', $current_class);
        })
        ->when($program_filter != 'All', function($query) use($program_filter){
            $query->where('program_id', $program_filter);
        })
        ->when($batch_filter != 'All', function($query) use($batch_filter){
            $query->where('batch', $batch_filter);
        })
        ->get(['id', 'program_id', 'fname', 'lname', 'mname', 'contact', 'batch']);

        foreach($student as $s){
            $soa = soa::where('stud_id', $s->id);
            $soa_clone = clone $soa;
            $s->due = $soa_clone->sum('amount_due');
            $s->paid = $soa_clone->sum('amount_paid');
            $s->balance =  number_format($s->due - $s->paid, 2, '.', '');
        }

        return Datatables::of($student)
        ->addColumn('name', function($data){
            return $data->lname . ', ' . $data->fname . ' ' . $data->mname;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '
                <form action="/excel_soa" method="POST" style="display: inline-block; margin-left: 10px;">
                    '.csrf_field().'
                    <input type="hidden" class="soa_id_hidden" name="soa_id_hidden" value="'.$data->id.'">
                    <button type="submit" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="left" title="Excel"><i class="fa fa-file-excel"></i></button>
                </form>
            ';

            return $html;
        })
        ->make(true);
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

    public function get_tf_payment(Request $request){
        return tf_payment::find($request->id);
    }

    public function get_sb_payment(Request $request){
        return sec_bond::find($request->id);
    }

    public function save_projection(Request $request){
        $prog_id = $request->prog_id;
        $proj_name_id = $request->proj_name_id;
        //1=SignUp,2=VisaProcessing,3=Language,4=Documentation,5=Selection,6=PDOS,7=Airfare,8=DHL
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

    public function save_tf_payment(Request $request){
        $id = $request->p_id;
        $add_edit = $request->p_add_edit;

        $tf_payment = ($add_edit == 'add') ? new tf_payment : tf_payment::find($id);
        $tf_payment->stud_id = $request->p_student;
        $tf_payment->tf_name_id = $request->type;
        $tf_payment->amount = $request->p_amount;
        $tf_payment->date = $request->p_date;
        $tf_payment->remarks = $request->p_remarks;
        $tf_payment->save();
    }

    public function save_sb_payment(Request $request){
        $id = $request->s_id;
        $add_edit = $request->s_add_edit;

        $tf_payment = ($add_edit == 'add') ? new sec_bond : sec_bond::find($id);
        $tf_payment->stud_id = $request->s_student;
        $tf_payment->amount = $request->s_amount;
        $tf_payment->date = $request->s_date;
        $tf_payment->remarks = $request->s_remarks;
        $tf_payment->save();
    }

    public function get_tf_student(Request $request){
        $name = $request->name;

        $student = student::with('program')->where('fname', 'LIKE', '%'.$name.'%')->orWhere('lname', 'LIKE', '%'.$name.'%')->get();

        $array = [];
        foreach ($student as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['fname'].' '.$value['lname'].' ('.$value['program']['name'].')'
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function delete_tf_payment(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $tf_payment = tf_payment::find($request->id);
        $tf_payment->delete();
        return $tf_payment->stud_id;
    }

    public function delete_sb_payment(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $sec_bond = sec_bond::find($request->id);
        $sec_bond->delete();
        return $sec_bond->stud_id;
    }
}