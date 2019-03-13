<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\books;
use App\lost_books;
use App\employee;
use Auth;
use Yajra\Datatables\Datatables;

class lostBookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function lost_book(Request $request){
        $id = $request->id;

        $book = books::find($id);

        $lost_book = new lost_books;
        $lost_book->book_id = $book->id;
        $lost_book->stud_id = ($book->stud_id) ? $book->stud_id : null;
        $lost_book->save();

        $book->status = 'Lost';
        $book->stud_id = null;
        $book->save();
    }

    public function view_books_lost(Request $request){
        $book_type_select = $request->book_type_select;
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch_name = $get_branch->branch->name;
        $branch = $get_branch->branch->id;

        $lost_books = lost_books::with('books.reference_no', 'books.book_type', 'student')->get();

        if($branch_name != 'Makati'){
            $lost_books = $lost_books->where('books.branch_id', $branch);
        }

        if($book_type_select != 'All'){
            $lost_books = $lost_books->where('books.book_type_id', $book_type_select);
        }

        return Datatables::of($lost_books)
        ->editColumn('stud_id', function($data){
            if($data->student){
                return $data->student->lname . ', ' . $data->student->fname . ' ' . $data->student->mname;
            }
        })
        ->make(true);
    }
}
