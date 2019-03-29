<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use App\student;
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
        $id = Auth::user()->emp_id;
        
        //Logout if resigned
        $employee = employee::find($id);

        if($employee->employment_status == 'Resigned'){
            return redirect()->to('/logout');
        }

        //User Referrals
        $referral_count = student::where('referral_id', $employee->id)->count();
        $student_count = student::count();

        return view('pages.dashboard', compact('referral_count', 'student_count'));
    }
}
