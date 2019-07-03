<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

    public function get_class(){
        $class_settings = class_settings::with('sensei', 'class_day.day_name', 
            'class_day.start_time', 'class_day.end_time')->get();

        return $class_settings;
    }

    public function sensei_class(Request $request){
        $completeCheck = $request->completeCheck;

        $class_settings_id = class_students::groupBy('class_settings_id')->pluck('class_settings_id');

        $class_settings = class_settings::with('sensei')->groupBy('sensei_id')
            ->whereHas('sensei', function ($query) use ($request){
                $query->where('fname', 'LIKE', '%'.$request->name.'%')->orWhere('lname', 'LIKE', '%'.$request->name.'%');
            })->when($completeCheck == false, function($query){
                $query->whereNotIn('id', $class_settings_id);
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
        info($request);

        $class_settings_id = class_students::groupBy('class_settings_id')->pluck('class_settings_id');

        $class_settings_test = class_settings::orderBy('start_date')->where('sensei_id', $sensei)->get();
        info($class_settings_test);

        $class_settings = class_settings::orderBy('start_date')->where('sensei_id', $sensei)
            ->where('start_date', 'LIKE', '%'.$request->name.'%')
            ->orWhere('end_date', 'LIKE', '%'.$request->name.'%')
            ->when($completeCheck == false, function($query) use ($class_settings_id){
                $query->whereNotIn('id', $class_settings_id);
            })->get();

        info($class_settings);

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
    
}
