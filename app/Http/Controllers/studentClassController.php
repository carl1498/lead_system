<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use Carbon\Carbon;
use Auth;
use Yajra\Datatables\Datatables;

class studentClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sensei = employee::withTrashed()->with('role')
            ->whereHas('role', function($query){
                $query->where('name', 'Language Head')->orWhere('name', 'Language Teacher');
            })->get();

        return view('pages.student_class', compact('sensei'));
    }
}
