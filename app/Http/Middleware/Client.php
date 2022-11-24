<?php

namespace App\Http\Middleware;
use App\User;
use Auth;

use Closure;

class Client
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $id = Auth::user()->id;
        $user = User::with('employee.role', 'employee.branch')->find($id);
        $authorized = ['President', 'Finance Director', 'HR/Finance Head', 'IT Officer', 
                        'Assistant Finance Officer', 'Marketing Head'];

        foreach($authorized as $auth){
            if($user->employee->role->name == $auth && $user->employee->employment_status != 'Resigned'
                || ($user->employee->role->name == 'Marketing Officer' && $user->employee->branch->name == 'Makati')){
                return $next($request);
            }
        }
    }
}
