<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\sec_bond;
use App\tf_name;
use App\tf_payment;
use App\tf_projected;
use App\tf_student;
use App\program;
use Yajra\Datatables\Datatables;

class tuitionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('pages.tuition');
    }

    public function view_tf_program(){
        $program = program::all();

    }
}
