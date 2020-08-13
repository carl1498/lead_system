<?php

namespace App\Http\Middleware;
use App\User;
use Auth;

use Closure;

class StudentClass
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
        $user = User::with('employee.role')->find($id);
        $authorized = ['President', 'Finance Director', 'Admin', 'Branch Manager', 'OIC', 'HR/Finance Head',
            'HR/Finance Officer', 'IT Officer', 'Marketing Manager', 'Marketing Head', 'Marketing Officer',
            'Documentation Head', 'Documentation Officer', 'Language Head', 'Language Teacher',
            'Assistant Finance Officer'];

        foreach($authorized as $auth){
            if($user->employee->role->name == $auth && $user->employee->employment_status != 'Resigned'){
                return $next($request);
            }
        }
        
        return redirect('/dashboard');
    }
}
