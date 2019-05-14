<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Auth;

class InvoiceHigherPermission
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
        $authorized = ['Language Head', 'President', 'Finance Director', 'HR/Finance Head', 'IT Officer'];

        foreach($authorized as $auth){
            if($user->employee->role->name == $auth){
                return $next($request);
            }
        }

        return redirect('/dashboard');
    }
}
