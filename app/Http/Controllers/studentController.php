<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student;
use Auth;
use Yajra\Datatables\Datatables;

class studentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $m = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();
        $makati = $m->where('branch.name', 'Makati');

        return view('pages.students', compact('makati'));
    }

    public function makati(){
        $m = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $makati = $m->where('branch.name', 'Makati');

        return $this->refreshDatatable($makati);
    }

    public function naga(){
        $n = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $naga = $n->where('branch.name', 'Naga');

        return $this->refreshDatatable($naga);
    }
    
    public function cebu(){
        $c = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $cebu = $c->where('branch.name', 'Cebu');

        return $this->refreshDatatable($cebu);
    }
    
    public function davao(){
        $d = student::with('program', 'school', 'benefactor', 'referral', 
        'branch', 'departure_year', 'departure_month')->get();

        $davao = $d->where('branch.name', 'Davao');

        return $this->refreshDatatable($davao);
    }

    public function refreshDatatable($branch){
        return Datatables::of($branch)
        ->editColumn('name', function($data){
            return $data->lname.', '.$data->fname.' '.$data->mname;
        })
        ->addColumn('action', function($data){
            return 'Actions here';
        })
        ->make(true);
    }
}
