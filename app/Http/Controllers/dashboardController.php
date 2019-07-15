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
        $referral_count = 0; $student_count = 0;

        $referral_count += student::where('referral_id', $employee->id)->whereNull('program_id')->count();
        $referral_count += student::with('program')->where('referral_id', $employee->id)
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })->count();

        $student_count += student::whereNull('program_id')->count();
        $student_count += student::with('program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })->count();

        //Birthdays
        $month = Carbon::now()->month;
        $student_birthdays = student::whereMonth('birthdate', $month)->get();
        foreach($student_birthdays as $student){
            $birthdate = $student['birthdate'];
            $birth = new Carbon($birthdate);
            
            $age = $birth->diffInYears(Carbon::now());

            $getDay = strtotime($student->birthdate);
            $birth_day = date('d', $getDay);
            $student = array_add($student, 'current_age', $age);
            $student = array_add($student, 'birth_day', $birth_day);
        }
        $employee_birthdays = employee::whereMonth('birthdate', $month)->get();
        foreach($employee_birthdays as $employee){
            $birthdate = $employee['birthdate'];
            $birth = new Carbon($birthdate);
            
            $age = $birth->diffInYears(Carbon::now());
            
            $getDay = strtotime($employee->birthdate);
            $birth_day = date('d', $getDay);
            $employee = array_add($employee, 'current_age', $age);
            $employee = array_add($employee, 'birth_day', $birth_day);
        }
        $merged_birthdays = $student_birthdays->merge($employee_birthdays)->sortBy('birth_day');

        //leaderboard
        $leaderboard = student::with('referral')
            ->whereHas('referral', function($query) {
                $query->where('employment_status', 'Active');
            })->groupBy('referral_id')->get();
        foreach($leaderboard as $l){
            $ref_count = student::where('referral_id', $l->referral_id)->count();
            $l = array_add($l, 'referral_count', $ref_count);
        }
        $leaderboard = $leaderboard->sortByDesc('referral_count');

        return view('pages.dashboard', compact('referral_count', 'student_count', 'merged_birthdays', 'leaderboard'));
    }

    public function update_signup_count(){
        $id = Auth::user()->emp_id;
        $employee = employee::find($id);
        $referral_count = 0; $student_count = 0;

        $referral_count += student::where('referral_id', $employee->id)->whereNull('program_id')->count();
        $referral_count += student::with('program')->where('referral_id', $employee->id)
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })->count();

        $student_count += student::whereNull('program_id')->count();
        $student_count += student::with('program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })->count();

        $output = array(
            'referral_count' => $referral_count,
            'student_count' => $student_count
        );

        echo json_encode($output);
    }

    public function monthly_referral(Request $request){
        $year = $request->year;
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;

        $all = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        $branch_array = [$all, $all, $all]; //Makati, Cebu, Davao | Just copied from all variable

        for($x = 0; $x < 12; $x++){
            for($y = 0; $y < 3; $y++){ //get students with null program because whereHas and whereNull does not work together
                $branch_array[$y][$x] += student::whereNull('program_id')->where('branch_id', $y+1)
                ->when($departure_year != 'All', function($query) use($departure_year){
                    $query->where('departure_year_id', $departure_year);
                })
                ->when($departure_month != 'All', function($query) use($departure_month){
                    $query->where('departure_month_id', $departure_month);
                })->whereMonth('date_of_signup', $x+1)->whereYear('date_of_signup', $year)->count();
        
                $branch_array[$y][$x] += student::with('program')->where('branch_id', $y+1)
                ->whereHas('program', function($query){
                    $query->where('name', '<>', 'SSW (Careworker)');
                    $query->where('name', '<>', 'SSW (Hospitality)');
                })
                ->when($departure_year != 'All', function($query) use($departure_year){
                    $query->where('departure_year_id', $departure_year);
                })
                ->when($departure_month != 'All', function($query) use($departure_month){
                    $query->where('departure_month_id', $departure_month);
                })
                ->whereMonth('date_of_signup', $x+1)->whereYear('date_of_signup', $year)->count();
            }

            //ALL
            $all[$x] += student::whereNull('program_id')->whereMonth('date_of_signup', $x+1)
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })->whereYear('date_of_signup', $year)->count();

            $all[$x] += student::with('program')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })->whereMonth('date_of_signup', $x+1)->whereYear('date_of_signup', $year)->count();
        }
        
        $output = array(
            'makati' => $branch_array[0],
            'cebu' => $branch_array[1],
            'davao' => $branch_array[2],
            'all' => $all
        );

        echo json_encode($output);
    }

    public function branch_signups(Request $request){
        $year = $request->year;
        $departure_year = $request->departure_year;
        $departure_month = $request->departure_month;
        $year_counter = ($request->year_counter == 1) ? 'true' : null;
        $all_total = 0; $all_active = 0; $all_backout = 0; $total = [0, 0, 0]; //Makati, Cebu, Davao
        $approved = [0, 0, 0]; $denied = [0, 0, 0]; $cancelled = [0, 0, 0]; 
        $final = [0, 0, 0]; $active = [0, 0, 0]; $backout = [0, 0, 0];

        //Total
        for($x = 0; $x < 3; $x++){
            $total[$x] += student::whereNull('program_id')->where('branch_id', $x+1)
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
            
            $total[$x] += student::with('program')->where('branch_id', $x+1)
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
        }

        $all_total += student::whereNull('program_id')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();

        $all_total += student::with('program')
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'SSW (Careworker)');
            $query->where('name', '<>', 'SSW (Hospitality)');
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();


        //IF APPROVED
        for($x = 0; $x < 3; $x++){
            $approved[$x] = student::where('branch_id', $x+1)->where('coe_status', 'Approved')
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
        }
        
        $all_approved = student::where('coe_status', 'Approved')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();

        for($x = 0; $x < 3; $x++){
            $denied[$x] = student::where('branch_id', $x+1)->where('coe_status', 'Denied')
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
        }

        $all_denied = student::where('coe_status', 'Denied')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();


        //IF CANCELLED
        for($x = 0; $x < 3; $x++){
            $cancelled[$x] = student::where('branch_id', $x+1)->where('status', 'Cancelled')
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
        }
        
        $all_cancelled = student::where('status', 'Cancelled')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();


        //IF FINAL SCHOOL
        for($x = 0; $x < 3; $x++){
            $final[$x] = student::where('branch_id', $x+1)->where('status', 'Final School')
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
        }

        $all_final = student::where('status', 'Final School')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();
        

        //IF ACTIVE
        for($x = 0; $x < 3; $x++){
            $active[$x] += student::where('branch_id', $x+1)->where('status', 'Active')->whereNull('program_id')
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();

            $active[$x] += student::with('program')->where('branch_id', $x+1)->where('status', 'Active')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
        }

        $all_active += student::where('status', 'Active')->whereNull('program_id')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();

        $all_active += student::with('program')->where('status', 'Active')
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'SSW (Careworker)');
            $query->where('name', '<>', 'SSW (Hospitality)');
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();
        

        //IF BACK OUT
        for($x = 0; $x < 3; $x++){
            $backout[$x] += student::where('branch_id', $x+1)->where('status', 'Back Out')->whereNull('program_id')
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();

            $backout[$x] += student::with('program')->where('branch_id', $x+1)->where('status', 'Back Out')
            ->whereHas('program', function($query){
                $query->where('name', '<>', 'SSW (Careworker)');
                $query->where('name', '<>', 'SSW (Hospitality)');
            })
            ->when($departure_year != 'All', function($query) use($departure_year){
                $query->where('departure_year_id', $departure_year);
            })
            ->when($departure_month != 'All', function($query) use($departure_month){
                $query->where('departure_month_id', $departure_month);
            })
            ->when($year_counter, function($query) use($year){
                $query->whereYear('date_of_signup', $year);
            })->count();
        }
        
        $all_backout += student::where('status', 'Back Out')->whereNull('program_id')
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();

        $all_backout += student::with('program')->where('status', 'Back Out')
        ->whereHas('program', function($query){
            $query->where('name', '<>', 'SSW (Careworker)');
            $query->where('name', '<>', 'SSW (Hospitality)');
        })
        ->when($departure_year != 'All', function($query) use($departure_year){
            $query->where('departure_year_id', $departure_year);
        })
        ->when($departure_month != 'All', function($query) use($departure_month){
            $query->where('departure_month_id', $departure_month);
        })
        ->when($year_counter, function($query) use($year){
            $query->whereYear('date_of_signup', $year);
        })->count();

        $all = [$all_approved, $all_denied, $all_cancelled, $all_final,
                $all_active, $all_backout, $all_total];

        $output = array(
            'total' => $total, 'approved' => $approved, 'denied' => $denied, 
            'cancelled' => $cancelled, 'final' => $final, 'active' => $active, 
            'backout' => $backout, 'all' => $all
        );

        echo json_encode($output);
    }

    public function get_current_year(){
        $current_year = Carbon::now()->year;
        $year = departure_year::where('name', $current_year)->first();
        return $year->id;
    }
}
