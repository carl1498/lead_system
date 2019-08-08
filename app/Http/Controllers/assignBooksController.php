<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\student;
use App\employee;
use App\books;
use App\book_type;
use App\User;
use App\assign_books;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Database\Eloquent\Builder;

class assignBooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_student(Request $request){
        $employee = employee::where('id', Auth::user()->emp_id)->first();
        $student = student::with('program')->where('lname', 'like', '%'.$request->name.'%')
            ->orWhere('fname', 'like', '%'.$request->name.'%')
            ->orWhere('mname', 'like', '%'.$request->name.'%')->get();

        $student = $student->where('branch_id', $employee->branch_id);
        $array = [];
        foreach ($student as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['lname'].', '.$value['fname'].' ('.$value['program']['name'].')'
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function get_available_book_type(Request $request){
        $student_id = $request->student_id;
        $employee = employee::where('id', Auth::user()->emp_id)->first();
        $limit_book = books::where('stud_id', $student_id)->groupBy('book_type_id')->pluck('book_type_id');

        $book_type = books::with('book_type')
            ->where('branch_id', $employee->branch_id)->where('status', 'Available')
            ->whereHas('book_type', function($query) use ($request) {
                $query->where('description', 'LIKE', '%'.$request->name.'%');
            })
            ->whereNotIn('book_type_id', $limit_book)
            ->groupBy('book_type_id')->get();
        
        $array = [];
        foreach ($book_type as $key => $value){
            $array[] = [
                'id' => $value['book_type_id'],
                'text' => $value['book_type']['description']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function get_available_book(Request $request){
        $employee = employee::where('id', Auth::user()->emp_id)->first();
        $book_type = $request->book_type;
        $book = books::where('book_type_id', $request->book_type)->where('branch_id', $employee->branch_id)
                    ->where('status', 'Available')->where('name', 'like', '%'.$request->name.'%')->get()->toArray();
        
        $array = [];
        foreach ($book as $key => $value){
            $array[] = [
                'id' => $value['name'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function save_book_assign(Request $request){
        $books = books::where('book_type_id', $request->assign_book_type)
            ->where('name', $request->assign_book)->first();
            
        $assign_books = new assign_books;
        $assign_books->book_id = $books->id;
        $assign_books->stud_id = $request->assign_student_name;
        $assign_books->save();

        
        $books->stud_id = $request->assign_student_name;
        $books->status = 'Released';
        $books->save();
    }

    public function view_assign_books(Request $request){
        $book_type_select = $request->book_type_select;
        $branch_select = $request->branch_select;
        $invoice_select = $request->invoice_select;
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->name;

        $assign_books = assign_books::with('books.book_type', 'books.reference_no', 'student.branch')->get();

        if($branch != 'Makati'){
            $assign_books = $assign_books->where('books.branch.name', $branch);
        }

        if($book_type_select != 'All'){
            $assign_books = $assign_books->where('books.book_type_id', $book_type_select);
        }

        if($branch_select != 'All'){
            $assign_books = $assign_books->where('books.branch_id', $branch_select);
        }

        if($invoice_select != 'All'){
            $assign_books = $assign_books->where('books.reference_no.id', $invoice_select);
        }

        return Datatables::of($assign_books)
        ->addColumn('student_name', function($data){
            return $data->student->lname . ', ' . $data->student->fname . ' ' . $data->student->mname;
        })
        ->make(true);
    }
}
