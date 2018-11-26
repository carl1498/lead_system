<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\school;
use Yajra\Datatables\Datatables;

class schoolController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.schools');
    }

    public function view(){
        $schools = school::all();

        return Datatables::of($schools)
        ->addColumn('action', function($data){
            return  '<button class="btn btn-warning btn-sm edit_school" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm delete_school" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
        })
        ->make(true);
    }

    public function save_school(Request $request){

        $add_edit = $request->add_edit;
        if($add_edit == 'add'){
            $school = new school;
            $school->name = $request->school_name;
            $school->save();
        }
        else{
            $id = $request->id;
            $school = school::find($id);
            $school->name = $request->school_name;
            $school->save();
        }
    }

    public function get_school(Request $request){
        $id = $request->id;
        $school = school::find($id);

        return $school;
    }

    public function delete_school(Request $request){
        $sch = school::where('id', $request->id)->delete();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
