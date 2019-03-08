<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\employee;
use App\pending_request;

class requestBooksController extends Controller
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

    public function get_pending(Request $request){
        $book_type = $request->book_type;
        
        $user = Auth::user()->emp_id;
        $get_branch = employee::with('branch')->where('id', $user)->first();

        $branch = $get_branch->branch->id;

        $pending = pending_request::where('book_type_id', $book_type)->where('branch_id', $branch)->first();
        
        if($pending){
            return $pending->pending;
        }else{
            return 0;
        }
    }
}
