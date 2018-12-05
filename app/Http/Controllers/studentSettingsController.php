<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\program;
use App\school;
use App\benefactor;
use App\departure_year;
use App\departure_month;
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
        /*else if($current_settings == 'Course'){
            $settings = course::all();
        }*/

        return Datatables::of($settings)
        ->addColumn('action', function($data){
            return  '<button class="btn btn-warning btn-sm edit_settings" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm delete_settings" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
        })
        ->make(true);
    }
}
