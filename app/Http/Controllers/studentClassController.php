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

class studentClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $sensei = employee::withTrashed()->with('role')
            ->whereHas('role', function($query){
                $query->where('name', 'Language Head')->orWhere('name', 'Language Teacher');
            })->get();

        return view('pages.student_class', compact('sensei'));
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

    public function get_class(Request $request){
        $current_tab = $request->current_class_tab;

        $class_settings = class_settings::with('sensei', 'class_day.day_name', 
            'class_day.start_time', 'class_day.end_time')->get();

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
                'class_day.start_time', 'class_day.end_time')->whereIn('id', $on_going)->get();
                break;
            case 'Complete':
                $class_settings = class_settings::with('sensei', 'class_day.day_name', 
                'class_day.start_time', 'class_day.end_time')->whereIn('id', $completed)->get();
                break;
            case 'All':
                break;
            default:
                break;
        }


        $output = array(
            'completed' => $completed_count,
            'on_going' => $on_going_count,
            'all' => $all,
            'class_settings' => $class_settings
        );

        return json_encode($output);
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
        $student = student::where('fname', 'LIKE', '%'.$request->name.'%')
            ->orWhere('lname', 'LIKE', '%'.$request->name.'%')->get();

        $array = [];
        foreach ($student as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['lname'].', '.$value['fname']
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

        if(isset($request->current_end_date)){
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
}
