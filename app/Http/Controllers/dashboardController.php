<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\employee;
use App\student;
use App\branch;
use App\departure_year;
use Auth;
use Carbon\Carbon;

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

        //Birthdays
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $day = Carbon::now()->day;
        $student_birthdays = student::whereMonth('birthdate', $month)->get();
        foreach($student_birthdays as $student){
            $birthdate = $student['birthdate'];
            $birth = explode('-', $birthdate);
            
            $age = $year - $birth[0];
            if($month == $birth[1]){
                if($day < $birth[2]){
                    $age--;
                }
            }
            else if($month < $birth[1]){
                $age--;
            }

            $getDay = strtotime($student->birthdate);
            $birth_day = date('d', $getDay);
            $student = array_add($student, 'current_age', $age);
            $student = array_add($student, 'birth_day', $birth_day);
        }
        $employee_birthdays = employee::whereMonth('birthdate', $month)->get();
        foreach($employee_birthdays as $employee){
            $birthdate = $employee['birthdate'];
            $birth = explode('-', $birthdate);
            
            $age = $year - $birth[0];
            if($month == $birth[1]){
                if($day < $birth[2]){
                    $age--;
                }
            }
            else if($month < $birth[1]){
                $age--;
            }
            
            $getDay = strtotime($employee->birthdate);
            $birth_day = date('d', $getDay);
            $employee = array_add($employee, 'current_age', $age);
            $employee = array_add($employee, 'birth_day', $birth_day);
        }
        $merged_birthdays = $student_birthdays->merge($employee_birthdays)->sortBy('birth_day');

        //leaderboard
        $leaderboard = student::with('referral')->groupBy('referral_id')->get();
        foreach($leaderboard as $l){
            $ref_count = student::where('referral_id', $l->referral_id)->count();
            $l = array_add($l, 'referral_count', $ref_count);
        }
        $leaderboard = $leaderboard->sortByDesc('referral_count');
        info($leaderboard);

        return view('pages.dashboard', compact('referral_count', 'student_count', 'merged_birthdays', 'leaderboard'));
    }

    public function update_signup_count(){
        $id = Auth::user()->emp_id;
        $employee = employee::find($id);
        $referral_count = student::where('referral_id', $employee->id)->count();
        $student_count = student::count();

        $output = array(
            'referral_count' => $referral_count,
            'student_count' => $student_count
        );

        echo json_encode($output);
    }

    public function monthly_referral(Request $request){
        $year = $request->year;
        $makati = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $cebu = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $davao = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

        for($x = 1; $x <= 12; $x++){
            //MAKATI
            $makati[$x-1] += student::with('referral.branch', 'program')
            ->whereNull('program_id')
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', $year)->count();

            $makati[$x-1] += student::with('referral.branch', 'program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'Language Only');
            })
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', $year)->count();

            //CEBU
            $cebu[$x-1] += student::with('referral.branch', 'program')
            ->whereNull('program_id')
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', $year)->count();

            $cebu[$x-1] = student::with('branch', 'program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'Language Only');
            })
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Cebu');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', $year)->count();

            //DAVAO
            $davao[$x-1] += student::with('referral.branch', 'program')
            ->whereNull('program_id')
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Makati');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', $year)->count();

            $davao[$x-1] = student::with('branch', 'program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'Language Only');
            })
            ->whereHas('referral.branch', function($query) {
                $query->where('name', 'Davao');
            })->whereMonth('date_of_signup', $x)->whereYear('date_of_signup', $year)->count();
        }
        
        $output = array(
            'makati' => $makati,
            'cebu' => $cebu,
            'davao' => $davao
        );

        echo json_encode($output);
    }

    public function branch_signups(Request $request){
        $year = $request->year;
        $makati = 0;
        $cebu = 0;
        $davao = 0;

        //MAKATI
        $makati += student::with('referral.branch')->whereYear('date_of_signup', $year)
        ->whereNull('program_id')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Makati');
        })->count();
        
        $makati += student::with('referral.branch')->whereYear('date_of_signup', $year)
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'Language Only');
        })
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Makati');
        })->count();

        //CEBU
        $cebu += student::with('referral.branch')->whereYear('date_of_signup', $year)
        ->whereNull('program_id')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Cebu');
        })->count();

        $cebu += student::with('referral.branch')->whereYear('date_of_signup', $year)
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'Language Only');
        })
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Cebu');
        })->count();

        //DAVAO
        $davao += student::with('referral.branch')->whereYear('date_of_signup', $year)
        ->whereNull('program_id')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Davao');
        })->count();

        $davao += student::with('referral.branch')->whereYear('date_of_signup', $year)
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
        })->whereYear('date_of_signup', $year)->count();

        $cebu_final = student::with('referral.branch')->where('status', 'Final School')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Cebu');
        })->whereYear('date_of_signup', $year)->count();

        $davao_final = student::with('referral.branch')->where('status', 'Final School')
        ->whereHas('referral.branch', function($query){
            $query->where('name', 'Davao');
        })->whereYear('date_of_signup', $year)->count();

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

    public function get_current_year(){
        $current_year = Carbon::now()->year;
        $year = departure_year::where('name', $current_year)->first();
        return $year->id;
    }
}
