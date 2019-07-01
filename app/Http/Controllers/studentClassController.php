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
        $class_settings = class_settings::with('sensei', 'class_day.day_name', 
            'class_day.start_time', 'class_day.end_time')->get();

        $sensei = employee::withTrashed()->with('role')
            ->whereHas('role', function($query){
                $query->where('name', 'Language Head')->orWhere('name', 'Language Teacher');
            })->get();

        return view('pages.student_class', compact('sensei', 'class_settings'));
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
}
