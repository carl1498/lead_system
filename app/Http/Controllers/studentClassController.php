<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student;
use App\employee;
use App\class_settings;
use App\class_students;
use App\time;
use App\class_day;
use Carbon\Carbon;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;

class studentClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sensei = employee::with('role')
            ->whereHas('role', function($query){
                $query->where('name', 'Language Head')->orWhere('name', 'Language Teacher');
            })->get();

        return view('pages.student_class', compact('sensei'));
    }

    public function class_students(Request $request){
        $current_class = $request->current_class_select;

        $class_settings = class_settings::find($current_class);

        $class_students = class_students::with('student', 'student.program', 'student.departure_year',
            'student.departure_month')->where('class_settings_id', $current_class)->get();

        return Datatables::of($class_students)
        ->addColumn('name', function($data){
            return $data->student->lname.', '.$data->student->fname.' '.$data->student->mname;
        })
        ->addColumn('departure', function($data){
            if($data->student->departure_month){
                return $data->student->departure_year->name . ' ' . $data->student->departure_month->name;
            }else{
                return 'N/A';
            }
        })
        ->addColumn('class_status', function($data){
            if($data->end_date && $data->student->status != 'Back Out'){
                return 'Complete';
            }
            else if($data->end_date && $data->student->status == 'Back Out'){
                return 'Back Out';
            }
            else{
                return 'Active';
            }
        })
        ->editColumn('end_date', function($data){
            if($data->end_date){
                return $data->end_date;
            }else{
                return 'TBD';
            }
        })
        ->addColumn('action', function($data){
            $html = '';
            
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Class History" class="btn btn-success btn-xs view_class_history" id="'.$data->stud_id.'"><i class="fa fa-eye"></i></button>&nbsp;';
            
            if(canAccessAll() || StudentClassHigherPermission()){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_student_date" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Remove" class="btn btn-danger btn-xs remove_student_class" id="'.$data->id.'"><i class="fa fa-user-minus"></i></button>&nbsp;';    
            }
            
            return $html;
        })
        ->make(true);
    }

    public function with_class_students(){
        $class_students = class_students::groupBy('stud_id')->pluck('stud_id');

        $student = student::with('program')->whereIn('id', $class_students)->get();

        return Datatables::of($student)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('departure', function($data){
            if($data->departure_month){
                return $data->departure_year->name . ' ' . $data->departure_month->name;
            }else{
                return 'N/A';
            }
        })
        ->addColumn('class_status', function($data){
            $class_students = class_students::with('student', 'current_class.sensei')->where('stud_id', $data->id)
                ->orderBy('id', 'desc')->first();

            $html = '';

            if($class_students->end_date && $class_students->student->status != 'Back Out'){
                $html .= 'Complete ';
            }
            else if($class_students->end_date && $class_students->student->status == 'Back Out'){
                $html .= 'Back Out ';
            }
            else{
                $html .= 'Active ';
            }

            $html .= '(' . $class_students->current_class->sensei->fname . ')';
            return $html;
        })
        ->addColumn('action', function($data){
            return '<button data-container="body" data-toggle="tooltip" data-placement="left" title="View Class History" class="btn btn-success btn-xs view_class_history" id="'.$data->id.'"><i class="fa fa-eye"></i></button>';
        })
        ->make(true);
    }

    public function no_class_students(){
        $class_students = class_students::groupBy('stud_id')->pluck('stud_id');

        $student = student::with('program')->whereNotIn('id', $class_students)->get();

        return Datatables::of($student)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('departure', function($data){
            if($data->departure_month){
                return $data->departure_year->name . ' ' . $data->departure_month->name;
            }else{
                return 'N/A';
            }
        })
        ->make(true);
    }

    public function all_class_students(){
        $student = student::with('program')->get();

        return Datatables::of($student)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('departure', function($data){
            if($data->departure_month){
                return $data->departure_year->name . ' ' . $data->departure_month->name;
            }else{
                return 'N/A';
            }
        })
        ->addColumn('class_status', function($data){
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

                $html .= '(' . $class_students->current_class->sensei->fname . ')';
                return $html;
            }else{
                return 'N/A';
            }
        })
        ->make(true);
    }

    public function add_class(Request $request){
        $add_start_time = $request->add_start_time;
        $add_end_time = $request->add_end_time;

        $class_settings = new class_settings;
        $class_settings->sensei_id = $request->sensei;
        $class_settings->start_date = $request->start_date;
        $class_settings->end_date = $request->end_date;
        $class_settings->remarks = $request->remarks;
        $class_settings->save();
        
        foreach($add_start_time as $s){
            if($s != null){
                $get_time = time::where('name', $s);
                if(empty($get_time)){
                    $time = new time;
                    $time->name = $s;
                    $time->save();
                }
            }
        }

        for($x = 0; $x < 6; $x++){
            $class_day = new class_day;
            $class_day->class_settings_id = $class_settings->id;
            $class_day->day_name_id = $x+1;

            $get_time = time::where('name', $add_start_time[$x])->first();
            if(empty($get_time)){
                if($add_start_time[$x] != null){
                    $time = new time;
                    $time->name = $add_start_time[$x];
                    $time->save();
                    $class_day->start_time_id = $time->id;
                }else{
                    $class_day->start_time_id = null;
                }
            }else{
                $class_day->start_time_id = $get_time->id;
            }
            
            $get_time = time::where('name', $add_end_time[$x])->first();
            if(empty($get_time)){
                if($add_end_time[$x] != null){
                    $time = new time;
                    $time->name = $add_end_time[$x];
                    $time->save();
                    $class_day->end_time_id = $time->id;
                }else{
                    $class_day->end_time_id = null;
                }
            }else{
                $class_day->end_time_id = $get_time->id;
            }

            $class_day->save();
        }
    }

    public function edit_class(Request $request){
        $edit_start_time = $request->edit_start_time;
        $edit_end_time = $request->edit_end_time;

        $class_settings = class_settings::with('class_day')->find($request->edit_class_id);
        $class_settings->sensei_id = $request->e_sensei;
        $class_settings->start_date = $request->e_start_date;
        $class_settings->end_date = $request->e_end_date;
        $class_settings->remarks = $request->e_remarks;
        
        foreach($edit_start_time as $s){
            if($s != null){
                $get_time = time::where('name', $s);
                if(empty($get_time)){
                    $time = new time;
                    $time->name = $s;
                    $time->save();
                }
            }
        }

        for($x = 0; $x < 6; $x++){
            $class_day = class_day::find($class_settings->class_day[$x]->id);
            $get_time = time::where('name', $edit_start_time[$x])->first();
            if(empty($get_time)){
                if($edit_start_time[$x] != null){
                    $time = new time;
                    $time->name = $edit_start_time[$x];
                    $time->save();
                    $class_day->start_time_id = $time->id;
                }else{
                    $class_day->start_time_id = null;
                }
            }else{
                $class_day->start_time_id = $get_time->id;
            }

            $get_time = time::where('name', $edit_end_time[$x])->first();
            if(empty($get_time)){
                if($edit_end_time[$x] != null){
                    $time = new time;
                    $time->name = $edit_end_time[$x];
                    $time->save();
                    $class_day->end_time_id = $time->id;
                }else{
                    $class_day->end_time_id = null;
                }
            }else{
                $class_day->end_time_id = $get_time->id;
            }

            $class_day->save();
        }

        $class_settings->save();
    }

    public function delete_class(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }
        $class_settings = class_settings::find($request->current_class_select);
        $class_settings->delete();
    }

    public function end_class(Request $request){
        $class_settings = class_settings::find($request->current_class_select);

        $class_students = class_students::where('class_settings_id', $class_settings->id)->get();
        
        foreach($class_students as $cs){
            if(empty($cs->end_date)){
                $cs->end_date = $class_settings->end_date;
                $cs->save();
            }
        }
    }

    public function view_student_class_history(Request $request){
        $id = $request->id;

        $class_students = class_students::with('current_class.sensei')->where('stud_id', $id)->get();

        return Datatables::of($class_students)
        ->addColumn('sensei', function($data){
            return $data->current_class->sensei->fname . ' ' . $data->current_class->sensei->lname;
        })
        ->make(true);
    }

    public function student_class_name(Request $request){
        $id = $request->id;

        $class_students = class_students::with('student')->where('stud_id', $id)->first();

        return $class_students->student->fname . ' ' . $class_students->student->lname;
    }

    public function edit_student_date(Request $request){
        $id = $request->edit_student_class_id;
        $class_students = class_students::find($id);

        $class_students->start_date = $request->e_student_start_date;
        $class_students->end_date = $request->e_student_end_date;
        $class_students->save();
    }

    public function remove_student_class(Request $request){
        $id = $request->id;
        $class_students = class_students::find($id);

        $class_students->delete();
    }

    public function get_class(Request $request){
        $current_tab = $request->current_class_tab;

        $class_settings = class_settings::with('sensei', 'class_day.day_name', 
            'class_day.start_time', 'class_day.end_time')->orderBy('start_date')->get();

        $check_on_going = class_students::whereNull('end_date')->groupBy('class_settings_id')->pluck('class_settings_id')->toArray();
        $check_no_students = class_students::groupBy('class_settings_id')->pluck('class_settings_id');
        $check_no_students = class_settings::whereNotIn('id', $check_no_students)->pluck('id')->toArray();
        
        $on_going = array_unique(array_merge($check_on_going, $check_no_students));

        $completed = class_settings::whereNotIn('id', $on_going)->pluck('id');
        $completed_count = class_settings::whereNotIn('id', $on_going)->count();
        $on_going_count = count($on_going);
        $all = $completed_count + $on_going_count;
        
        switch($current_tab){
            case 'Ongoing':
                $class_settings = class_settings::with('sensei', 'class_day.day_name', 
                'class_day.start_time', 'class_day.end_time')->whereIn('id', $on_going)
                ->orderBy('start_date')->get();
                break;
            case 'Complete':
                $class_settings = class_settings::with('sensei', 'class_day.day_name', 
                'class_day.start_time', 'class_day.end_time')->whereIn('id', $completed)
                ->orderBy('start_date')->get();
                break;
            case 'All':
                break;
            default:
                break;
        }

        foreach($class_settings as $cs){
            $cs->complete = class_students::with('student')->where('class_settings_id', $cs->id)
                ->whereHas('student', function($query){
                    $query->where('status', '<>', 'Back Out');
                })->whereNotNull('end_date')->count();

            $cs->backout = class_students::with('student')->where('class_settings_id', $cs->id)
                ->whereHas('student', function($query){
                    $query->where('status', 'Back Out');
                })->whereNotNull('end_date')->count();

            $cs->active = class_students::where('class_settings_id', $cs->id)->whereNull('end_date')->count();

            $cs->all = class_students::where('class_settings_id', $cs->id)->count();
        }

        $output = array(
            'completed' => $completed_count,
            'on_going' => $on_going_count,
            'all' => $all,
            'class_settings' => $class_settings
        );

        return json_encode($output);
    }

    public function get_student_date(Request $request){
        return class_students::with('student')->find($request->id);
    }

    public function sensei_class(Request $request){
        $completeCheck = $request->completeCheck;

        $check_on_going = class_students::whereNull('end_date')->groupBy('class_settings_id')->pluck('class_settings_id')->toArray();
        $check_no_students = class_students::groupBy('class_settings_id')->pluck('class_settings_id');
        $check_no_students = class_settings::whereNotIn('id', $check_no_students)->pluck('id')->toArray();
        
        $on_going = array_unique(array_merge($check_on_going, $check_no_students));

        $class_settings = class_settings::with('sensei')->groupBy('sensei_id')
            ->whereHas('sensei', function ($query) use ($request){
                $query->where('fname', 'LIKE', '%'.$request->name.'%')->orWhere('lname', 'LIKE', '%'.$request->name.'%');
            })->when($completeCheck == 'false', function($query) use($on_going){
                $query->whereIn('id', $on_going);
            })->get();

        $array = [];
        foreach ($class_settings as $key => $value){
            $array[] = [
                'id' => $value['sensei_id'],
                'text' => $value['sensei']['fname'].' '.$value['sensei']['lname']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function date_class(Request $request){
        $completeCheck = $request->completeCheck;
        $sensei = $request->sensei;

        $check_on_going = class_students::whereNull('end_date')->groupBy('class_settings_id')->pluck('class_settings_id')->toArray();
        $check_no_students = class_students::groupBy('class_settings_id')->pluck('class_settings_id');
        $check_no_students = class_settings::whereNotIn('id', $check_no_students)->pluck('id')->toArray();
        
        $on_going = array_unique(array_merge($check_on_going, $check_no_students));

        $class_settings = class_settings::where('sensei_id', $sensei)->where('start_date', 'LIKE', '%'.$request->name.'%')
            ->when($completeCheck == 'false', function($query) use ($on_going){
                $query->whereIn('id', $on_going);
            })->orderBy('start_date')->get();

        foreach($class_settings as $cs){
            if($cs->end_date == null){
                $cs->end_date = 'TBD';
            }
        }

        $array = [];
        foreach ($class_settings as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['start_date'].' ~ '.$value['end_date']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function student_class(Request $request){
        $student = student::with('program')->where('fname', 'LIKE', '%'.$request->name.'%')
            ->orWhere('lname', 'LIKE', '%'.$request->name.'%')->get();

        $array = [];
        foreach ($student as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['lname'].', '.$value['fname'].' ('.$value['program']['name'].')'
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function check_student_class(Request $request){
        $student_id = $request->student;
        
        $class_students = class_students::with('current_class.sensei')
            ->where('stud_id', $student_id)->whereNull('end_date')
            ->orderBy('id', 'desc')->first();

        if(empty($class_students)){
            return 'false';
        }else{
            return $class_students;
        }
    }

    public function assign_student_class(Request $request){
        $start_date = class_settings::find($request->date_class);
        $sensei_class = $request->sensei_class;

        if(isset($request->current_end_date)){
            $student_exist = class_students::with('current_class')->where('id', $request->class_students_id)
                ->whereHas('current_class', function($query) use($sensei_class){
                    $query->where('sensei_id', $sensei_class);
                })->whereNull('end_date')->first();

            if(!empty($student_exist)){
                return 'assigned';
            }

            $end_date = class_students::find($request->class_students_id);
            $end_date->end_date = Carbon::parse($request->current_end_date);
            $end_date->save();
        }

        $class_students = new class_students;
        $class_students->class_settings_id = $request->date_class;
        $class_students->stud_id = $request->student_class;
        $class_students->start_date = $start_date->start_date;
        $class_students->save();
    }

    public function get_class_settings(Request $request){
        $class_settings = class_settings::with('class_day', 'class_day.start_time', 'class_day.end_time')
            ->find($request->current_class_select);

        return $class_settings;
    }
}
