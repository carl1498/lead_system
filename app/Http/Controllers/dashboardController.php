<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use App\student;
use App\branch;
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

    public function monthly_referral(){
        $makati = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $cebu = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $davao = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        for($x = 1; $x <= 12; $x++){
            //MAKATI
            $makati[$x-1] += student::with('referral.branch', 'program')
            ->whereNull('program_id')
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', '2019')->count();

            $makati[$x-1] += student::with('referral.branch', 'program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'Language Only');
            })
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', '2019')->count();

            //CEBU
            $cebu[$x-1] += student::with('referral.branch', 'program')
            ->whereNull('program_id')
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', '2019')->count();

            $cebu[$x-1] = student::with('branch', 'program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'Language Only');
            })
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Cebu');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', '2019')->count();

            //DAVAO
            $davao[$x-1] += student::with('referral.branch', 'program')
            ->whereNull('program_id')
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', '2019')->count();

            $davao[$x-1] = student::with('branch', 'program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'Language Only');
            })
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Davao');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', '2019')->count();
        }
        
        $output = array(
            'makati' => $makati,
            'cebu' => $cebu,
            'davao' => $davao
        );

        echo json_encode($output);
    }

    public function branch_signups(){
        $makati = 0;
        $cebu = 0;
        $davao = 0;

        //MAKATI
        $makati += student::with('referral.branch')
        ->whereNull('program_id')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Makati');
        })->count();
        
        $makati += student::with('referral.branch')
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'Language Only');
        })
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Makati');
        })->count();

        //CEBU
        $cebu += student::with('referral.branch')
        ->whereNull('program_id')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Makati');
        })->count();

        $cebu = student::with('referral.branch')
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'Language Only');
        })
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Cebu');
        })->count();

        //DAVAO
        $davao += student::with('referral.branch')
        ->whereNull('program_id')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Makati');
        })->count();

        $davao = student::with('referral.branch')
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'Language Only');
        })
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Davao');
        })->count();

        //IF FINAL SCHOOL
        $makati_final = student::with('referral.branch')->where('status', 'Final School')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Makati');
        })->count();

        $cebu_final = student::with('referral.branch')->where('status', 'Final School')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Cebu');
        })->count();

        $davao_final = student::with('referral.branch')->where('status', 'Final School')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Davao');
        })->count();

        $output = array(
            'makati' => $makati,
            'cebu' => $cebu,
            'davao' => $davao,
            'makati_final' => $makati_final,
            'cebu_final' => $cebu_final,
            'davao_final' => $davao_final
        );

        echo json_encode($output);
    }
}
