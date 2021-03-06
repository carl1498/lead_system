<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\benefactor;
use Yajra\Datatables\Datatables;

class benefactorController extends Controller
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
        return view('pages.benefactors');
    }

    public function view(){
        $benefactors = benefactor::all();

        return Datatables::of($benefactors)
        ->addColumn('action', function($data){
            return  '<button class="btn btn-warning btn-sm edit_benefactor" id="'.$data->id.'"><i class="fa fa-pen"></i></button>
                    <button class="btn btn-danger btn-sm delete_benefactor" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
        })
        ->make(true);
    }

    public function save_benefactor(Request $request){
        $add_edit = $request->add_edit;
        if($add_edit == 'add'){
            $benefactor = new benefactor;
            $benefactor->name = $request->benefactor_name;
            $benefactor->save();
        }
        else{
            $id = $request->id;
            $benefactor = benefactor::find($id);
            $benefactor->name = $request->benefactor_name;
            $benefactor->save();
        }
    }

    public function get_benefactor(Request $request){
        $id = $request->id;
        $benefactor = benefactor::find($id);

        return $benefactor;
    }

    public function delete_benefactor(Request $request){
        $benefactor = benefactor::find($request->id);
        $benefactor->delete();
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
