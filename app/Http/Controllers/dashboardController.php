<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use Auth;

class dashboardController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::user()->id;
        $employee = employee::find($id);

        if($employee->employment_status == 'Resigned'){
            return redirect()->to('/logout');
        }
        
        return view('pages.dashboard');
    }
}
