<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\program;
use App\school;
use App\benefactor;
use App\departure_year;
use App\departure_month;
use App\course;
use App\company;
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
        $settings_type = ['Program', 'School', 'Benefactor', 'Year', 'Month', 'Course', 'Company'];
        $get_settings = [program::all(), school::all(), benefactor::all(),
            departure_year::all(), departure_month::all(), course::all(), company::all()];

        for($x = 0; $x < count($settings_type); $x++){
            if($current_settings == $settings_type[$x]){
                $settings = $get_settings[$x];
                
            }
        }

        return Datatables::of($settings)
        ->addColumn('action', function($data) use($current_settings){
            $html = '';
            if($current_settings == 'Program'){
                if($data->name == 'Language Only' || $data->name == 'SSW (Careworker)' ||
                $data->name == 'SSW (Hospitality)' || $data->name == 'TITP' || $data->name == 'TITP (Careworker)'){
                    return;
                }
            }
            return  '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-warning btn-sm edit_student_settings" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            //<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-sm delete_student_settings" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>
        })
        ->make(true);
    }

    public function store(Request $request){
        $id = $request->id;
        $current_settings = $request->current_settings;
        $settings_type = ['Program', 'School', 'Benefactor', 'Year', 'Month', 'Course', 'Company'];
        $new_settings = [new program, new school, new benefactor, new departure_year,
            new departure_month, new course, new company];
        $edit_settings = [program::find($id), school::find($id), benefactor::find($id),
            departure_year::find($id), departure_month::find($id),
            course::find($id), company::find($id)];
            info($request);

        if($request->add_edit == 'add'){
            for($x = 0; $x < count($settings_type); $x++){
                if($current_settings == $settings_type[$x]){
                    $settings = $new_settings[$x];
                    break;
                }
            }
        }
        else{
            for($x = 0; $x < count($settings_type); $x++){
                info($settings_type[$x]);
                if($current_settings == $settings_type[$x]){
                    $settings = $edit_settings[$x];
                    break;
                }
            }
        }

        $settings->name = $request->student_settings_name;
        $settings->save();
    }

    public function get_student_settings(Request $request){
        $id = $request->id;
        $current_settings = $request->current_settings;
        $settings_type = ['Program', 'School', 'Benefactor', 'Year', 'Month', 'Course', 'Company'];
        $get_settings = [program::find($id), school::find($id), benefactor::find($id),
            departure_year::find($id), departure_month::find($id),
            course::find($id), company::find($id)];

        for($x = 0; $x < count($settings_type); $x++){
            if($current_settings == $settings_type[$x]){
                $settings = $get_settings[$x];
                break;
            }
        }

        return $settings->name;
    }

    public function delete_student_settings(Request $request){
        $id = $request->id;
        $current_settings = $request->current_settings;
        $settings_type = ['Program', 'School', 'Benefactor', 'Year', 'Month', 'Course', 'Company'];
        $get_settings = [program::find($id), school::find($id), benefactor::find($id),
            departure_year::find($id), departure_month::find($id),
            course::find($id), company::find($id)];

        for($x = 0; $x < count($settings_type); $x++){
            if($current_settings == $settings_type[$x]){
                $settings = $get_settings[$x];
                break;
            }
        }

        $settings->delete();
    }
}
