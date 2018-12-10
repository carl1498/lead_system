<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\program;
use App\school;
use App\benefactor;
use App\departure_year;
use App\departure_month;
use App\course;
use Yajra\Datatables\Datatables;

class studentSettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('pages.student_settings');
    }

    public function view(Request $request){
        $current_settings = $request->current_settings;

        if($current_settings == 'Program'){
            $settings = program::all();
        }
        else if($current_settings == 'School'){
            $settings = school::all();
        }
        else if($current_settings == 'Benefactor'){
            $settings = benefactor::all();
        }
        else if($current_settings == 'Year'){
            $settings = departure_year::all();
        }
        else if($current_settings == 'Month'){
            $settings = departure_month::all();
        }
        else if($current_settings == 'Course'){
            $settings = course::all();
        }

        return Datatables::of($settings)
        ->addColumn('action', function($data){
            return  '<button class="btn btn-warning btn-sm edit_sstudent_ettings" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm delete_sstudent_settings" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
        })
        ->make(true);
    }

    public function store(Request $request){
        $id = $request->id;
        $current_settings = $request->current_settings;

        if($request->add_edit == 'add'){
            if($current_settings == 'Program'){
                $settings = new program;
            }
            else if($current_settings == 'School'){
                $settings = new school;
            }
            else if($current_settings == 'Benefactor'){
                $settings = new benefactor;
            }
            else if($current_settings == 'Year'){
                $settings = new departure_year;
            }
            else if($current_settings == 'Month'){
                $settings = new departure_month;
            }
            else if($current_settings == 'Course'){
                $settings = new course;
            }
        }
        else{
            if($current_settings == 'Program'){
                $settings = program::find($id);
            }
            else if($current_settings == 'School'){
                $settings = school::find($id);
            }
            else if($current_settings == 'Benefactor'){
                $settings = benefactor::find($id);
            }
            else if($current_settings == 'Year'){
                $settings = departure_year::find($id);
            }
            else if($current_settings == 'Month'){
                $settings = departure_month::find($id);
            }
            else if($current_settings == 'Course'){
                $settings = course::find($id);
            }
        }

        /*$settings->name = $request->student_settings_name;
        $settings->save(); */

        return $current_settings;
    }

    public function get_student_settings(){
        $id = $request->id;
        $current_settings = $request->current_settings;

        if($current_settings == 'Program'){
            $settings = program::find($id);
        }
        else if($current_settings == 'School'){
            $settings = school::find($id);
        }
        else if($current_settings == 'Benefactor'){
            $settings = benefactor::find($id);
        }
        else if($current_settings == 'Year'){
            $settings = departure_year::find($id);
        }
        else if($current_settings == 'Month'){
            $settings = departure_month::find($id);
        }
        else if($current_settings == 'Course'){
            $settings = course::find($id);
        }

        return $settings->name;
    }
}
