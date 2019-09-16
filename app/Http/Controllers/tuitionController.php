<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sec_bond;
use App\tf_name;
use App\tf_payment;
use App\tf_projected;
use App\tf_student;
use App\program;
use Yajra\Datatables\Datatables;

class tuitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('pages.tuition');
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

    public function get_tf_projected(Request $request){
        $tf_name_list = [1, 2, 3, 4, 5, 6];
        $tf_projected = tf_projected::where('program_id', $request->id)->get();

        $output = array(
            'tf_name_list' => $tf_name_list,
            'tf_projected' => $tf_projected,
        );

        echo json_encode($output);
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
}