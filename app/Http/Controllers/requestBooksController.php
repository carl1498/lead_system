<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\employee;
use App\pending_request;
use App\request_books;
use Yajra\Datatables\Datatables;

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

        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->id;

        $pending = pending_request::where('book_type_id', $book_type)->where('branch_id', $branch)->first();
        
        if($pending){
            return $pending->pending;
        }
        else{
            return 0;
        }
    }

    public function save_book_request(Request $request){
        $book_type = $request->request_book;

        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->id;
        
        $pending_request = pending_request::where('book_type_id', $book_type)->where('branch_id', $branch)->first();

        if($pending_request){
            $pending_request->pending = $request->request_pending;
            $pending_request->save();
        }
        else{
            $pending_request = new pending_request;
            $pending_request->book_type_id = $book_type;
            $pending_request->branch_id = $branch;
            $pending_request->pending = $request->request_pending;
            $pending_request->save();
        }

        $pending_request = pending_request::where('book_type_id', $book_type)->where('branch_id', $branch)->first();
        $pending_request_id = $pending_request->id;

        $request_books = new request_books;
        $request_books->p_request_id = $pending_request_id;
        $request_books->previous_pending = $request->request_previous_pending;
        $request_books->quantity = $request->request_quantity;
        $request_books->pending = $request->request_pending;
        $request_books->remarks = $request->request_remarks;
        $request_books->save();
    }

    public function view_request_books(Request $request){
        $book_type_select = $request->book_type_select;
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->name;

        $request_books = request_books::with('pending_request.book_type', 'pending_request.branch')->get();
        
        if($branch != 'Makati'){
            $request_books = $request_books->where('pending_request.branch.name', $branch);
        }

        if($book_type_select != 'All'){
            $request_books = $request_books->where('pending_request.book_type_id', $book_type_select);
        }
        
        return Datatables::of($request_books)
        ->addColumn('action', function($data){
            return 'TEMP';
        })
        ->make(true);
    }
}
