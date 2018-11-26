<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use App\branch;
use App\role;
use App\User;
use Auth;
use Yajra\Datatables\Datatables;

class employeeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        $branch = branch::all();
        $role = role::all();

        return view('pages.employees', compact('branch', 'role'));
    }

    public function makati(){
        $id = Auth::user()->emp_id;
        $m = employee::with('role')->with('branch')->get();
        $makati = $m->where('branch.name', 'Makati');

        return Datatables::of($makati)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->addColumn('action', function($data){
            return 'Actions here';
        })
        ->make(true);
    }

    public function naga(){
        $id = Auth::user()->emp_id;
        $n = employee::with('role', 'branch')->get();
        $naga = $n->where('branch.name', 'Naga');

        return Datatables::of($naga)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->addColumn('action', function($data){
            return 'Actions here';
        })
        ->make(true);
    }

    public function cebu(){
        $id = Auth::user()->emp_id;
        $c = employee::with('role')->with('branch')->get();
        $cebu = $c->where('branch.name', 'Cebu');

        return Datatables::of($cebu)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->addColumn('action', function($data){
            return 'Actions here';
        })
        ->make(true);
    }

    public function davao(){
        $id = Auth::user()->emp_id;
        $d = employee::with('role')->with('branch')->get();
        $davao = $d->where('branch.name', 'Davao');

        return Datatables::of($davao)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname; 
        })
        ->addColumn('action', function($data){
            return 'Actions here';
        })
        ->make(true);
    }
}
