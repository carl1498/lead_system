<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;

class StudentList
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
            'Documentation Head', 'Documentation Officer', 'Language Head', 'Intern', 'Assistant Finance Officer'];

        foreach($authorized as $auth){
            if($user->employee->role->name == $auth){
                return $next($request);
            }
        }
        
        return redirect('/dashboard');
    }
}
