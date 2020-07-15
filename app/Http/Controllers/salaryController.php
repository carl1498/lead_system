<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\employee;
use App\emp_salary;
use App\salary_monitoring;
use App\salary_income;
use App\salary_deduction;
use Auth;
use Yajra\Datatables\Datatables;

class salaryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('pages.salary');
    }

    public function view_employee_salary(){
        $emp_salary = emp_salary::with('employee.company_type', 'employee.branch')->get();

        return Datatables::of($emp_salary)
        ->addColumn('name', function($data){
            info($data->employee->lname);
            return $data->employee->lname . ', ' . $data->employee->fname . ' ' . $data->employee->mname;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-info btn-xs edit_employee_salary" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            return $html;
        })
        ->make(true);
    }
}
