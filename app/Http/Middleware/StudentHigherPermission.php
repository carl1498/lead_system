<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;

class StudentHigherPermission
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
        $authorized = ['OIC', 'President', 'Finance Director', 'HR/Finance Head', 'IT Officer', 'Marketing Officer',
            'Marketing Head', 'Marketing Manager', 'Documentation Head', 'Documentation Officer',
            'Assistant Finance Officer'];

        foreach($authorized as $auth){
            if($user->employee->role->name == $auth && $user->employee->employment_status != 'Resigned'){
                return $next($request);
            }
        }

        return redirect('/dashboard');
    }
}
