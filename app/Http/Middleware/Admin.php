<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\User;

class Admin
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
        $authorized = ['President', 'Finance Director', 'HR/Finance Head', 'Assistant Finance Officer',
            'IT Officer'];

        foreach($authorized as $auth){
            if($user->employee->role->name == $auth && $user->employee->employment_status != 'Resigned'){
                return $next($request);
            }
        }

        return redirect('/dashboard');
    }
}
