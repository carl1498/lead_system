<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student_edit_history;
use App\student_delete_history;
use Yajra\Datatables\Datatables;

class studentLogsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit_history_page(){
        return view('pages.student_edit_history');
    }

    public function delete_history_page(){
        return view('pages.student_delete_history');
    }

    public function edit_history_table(){
        $edit_history = student_edit_history::with('student', 'edited_by')->get();

        return Datatables::of($edit_history)
        ->editColumn('stud_id', function($data){
            return $data->student->lname . ', ' . $data->student->fname . ' ' . $data->student->mname;
        })
        ->make(true);
    }

    public function delete_history_table(){
        $delete_history = student_delete_history::with('student', 'deleted_by')->get();

        return Datatables::of($delete_history)
        ->editColumn('stud_id', function($data){
            return $data->student->lname . ', ' . $data->student->fname . ' ' . $data->student->mname;
        })
        ->make(true);
    }
}
