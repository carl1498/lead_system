<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
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
        return view('pages.employees');
    }

    public function makatiEmployee(){
        $id = Auth::user()->emp_id;
        $mak = User::with('employee.branch')->get();

        return Datatables::of($mak)
        ->addColumn('action', function($data){
            return 'Actions here';
        })
        ->make(true);
    }
}
