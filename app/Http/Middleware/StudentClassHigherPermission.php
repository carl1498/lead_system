<?php

namespace App\Http\Middleware;
use App\User;
use Auth;

use Closure;

class StudentClassHigherPermission
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
        $authorized = ['President', 'Finance Director', 'HR/Finance Head', 'Language Head', 'IT Officer'];

        foreach($authorized as $auth){
            if($user->employee->role->name == $auth){
                return $next($request);
            }
        }

        return redirect('/dashboard');
    }
}
