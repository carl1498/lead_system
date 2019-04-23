<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\book_type;
use App\branch;
use App\books;
use App\employee;
use App\student;
use App\program;
use App\pending_request;
use App\reference_no;
use App\invoice;
use Auth;
use Yajra\Datatables\Datatables;

class bookManagementController extends Controller
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
        $book_type = book_type::all();
        $branch = branch::all();
        $program = program::all();
        $invoice = reference_no::all();

        return view('pages.book_management', compact('book_type', 'branch', 'program', 'invoice'));
    }

    public function view_books(Request $request){
        $book_type_select = $request->book_type_select;
        $branch_select = $request->branch_select;
        $invoice_select = $request->invoice_select;
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->name;

        $books = books::with('student', 'book_type', 'branch', 'reference_no')->get();

        if($branch != 'Makati'){
            $books = $books->where('branch.name', $branch);
        }

        if($book_type_select != 'All'){
            $books = $books->where('book_type.id', $book_type_select);
        }

        if($branch_select != 'All'){
            $books = $books->where('branch_id', $branch_select);
        }

        if($invoice_select != 'All'){
            $books = $books->where('reference_no.id', $invoice_select);
        }

        return Datatables::of($books)
        ->addColumn('student_name', function($data){
            if($data->student){
                return $data->student->lname . ', ' . $data->student->fname . ' ' . $data->student->mname; 
            }
        })
        ->addColumn('action', function($data){
            $action = '';
            if($data->status != 'Lost'){
                $action .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Lost" class="btn btn-danger btn-xs lost_book" id="'.$data->id.'"><i class="fas fa-eye-slash"></i></button>&nbsp;&nbsp;';
            }
            if($data->student || $data->status == 'Lost'){
                $action .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Return" class="btn btn-warning btn-xs return_book" id="'.$data->id.'"><i class="fas fa-undo"></i></button>';
            }
           
            return $action;
        })
        ->make(true);
    }

    public function view_student_books(Request $request){
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->name;
        $student_status = $request->student_status_select;
        $program = $request->program_select;
        $branch_select = $request->branch_select;
        $student = student::with('branch', 'program', 'departure_year', 'departure_month')->get();
        if($branch != 'Makati'){
            $student = $student->where('branch.name', $branch);
        }

        if($student_status != 'All'){
            $student = $student->where('status', $student_status);
        }

        if($program != 'All'){
            $student = $student->where('program_id', $program);
        }
        
        if($branch_select != 'All'){
            $student = $student->where('branch_id', $branch_select);
        }

        return Datatables::of($student)
        ->addColumn('student_name', function($data){
            return $data->lname . ', ' . $data->fname . ' ' . $data->mname;
        })
        ->addColumn('book_1', function($data){
            $get_book = book_type::where('name', 'Book 1')->first();
            $get_book_id = $get_book->id;
            $book = books::where('stud_id', $data->id)->where('book_type_id', $get_book_id)->first();

            if($book){
                return $book->name;
            }
        })
        ->addColumn('wb_1', function($data){
            $get_book = book_type::where('name', 'WB 1')->first();
            $get_book_id = $get_book->id;
            $book = books::where('stud_id', $data->id)->where('book_type_id', $get_book_id)->first();

            if($book){
                return $book->name;
            }
        })
        ->addColumn('book_2', function($data){
            $get_book = book_type::where('name', 'Book 2')->first();
            $get_book_id = $get_book->id;
            $book = books::where('stud_id', $data->id)->where('book_type_id', $get_book_id)->first();

            if($book){
                return $book->name;
            }
        })
        ->addColumn('wb_2', function($data){
            $get_book = book_type::where('name', 'WB 2')->first();
            $get_book_id = $get_book->id;
            $book = books::where('stud_id', $data->id)->where('book_type_id', $get_book_id)->first();

            if($book){
                return $book->name;
            }
        })
        ->addColumn('kanji', function($data){
            $get_book = book_type::where('name', 'Kanji')->first();
            $get_book_id = $get_book->id;
            $book = books::where('stud_id', $data->id)->where('book_type_id', $get_book_id)->first();

            if($book){
                return $book->name;
            }
        })
        ->addColumn('action', function($data){
            return 'TEMP';
        })
        ->addColumn('departure', function($data){
            if($data->program){
                if($data->program->name == 'Language Only'){
                    return 'N/A';
                }
            }

            return $data->departure_year->name . ' ' . $data->departure_month->name;
        })
        ->make(true);
    }

    public function view_branch_books(Request $request){
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch_name = $get_branch->branch->name;
        $branch = branch::all();

        $status = $request->book_status;
        $invoice_select = $request->invoice_select;
        
        if($branch_name != 'Makati'){
            $branch = $branch->where('name', $branch_name);
        }

        return Datatables::of($branch)
        ->addColumn('book_1', function($data) use($status, $invoice_select){
            $get_book = book_type::where('name', 'Book 1')->first();
            $get_book_id = $get_book->id;

            if($status == 'All'){
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)
                        ->where('invoice_ref_id', $invoice_select)->count();
                }
            }
            else if($status == 'Pending'){
                if($data->name == 'Makati'){
                    if($invoice_select == 'All'){
                        $invoice = invoice::where('book_type_id', $get_book_id)->get();
                        $book = 0;
                        foreach($invoice as $i){
                            $book += $i->pending;
                        }
                    }
                    else{
                        $book = invoice::where('book_type_id', $get_book_id)
                            ->where('ref_no_id', $invoice_select)->first();
                        $book = $book->pending;
                    }
                }
                else{
                    $book = pending_request::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->first();
                    $book = ($book) ? $book->pending : 0;
                }
            }
            else{
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('status', $status)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('status', $status)
                        ->where('book_type_id', $get_book_id)->where('invoice_ref_id', $invoice_select)->count();
                }
            }

            $book = ($book) ? $book : 0;
            return $book;
        })
        ->addColumn('wb_1', function($data) use($status, $invoice_select){
            $get_book = book_type::where('name', 'WB 1')->first();
            $get_book_id = $get_book->id;

            if($status == 'All'){
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)
                        ->where('invoice_ref_id', $invoice_select)->count();
                }
            }
            else if($status == 'Pending'){
                if($data->name == 'Makati'){
                    if($invoice_select == 'All'){
                        $invoice = invoice::where('book_type_id', $get_book_id)->get();
                        $book = 0;
                        foreach($invoice as $i){
                            $book += $i->pending;
                        }
                    }
                    else{
                        $book = invoice::where('book_type_id', $get_book_id)
                            ->where('ref_no_id', $invoice_select)->first();
                        $book = $book->pending;
                    }
                }
                else{
                    $book = pending_request::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->first();
                    $book = ($book) ? $book->pending : 0;
                }
            }
            else{
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('status', $status)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('status', $status)
                        ->where('book_type_id', $get_book_id)->where('invoice_ref_id', $invoice_select)->count();
                }
            }

            $book = ($book) ? $book : 0;
            return $book;
        })
        ->addColumn('book_2', function($data) use($status, $invoice_select){
            $get_book = book_type::where('name', 'Book 2')->first();
            $get_book_id = $get_book->id;

            if($status == 'All'){
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)
                        ->where('invoice_ref_id', $invoice_select)->count();
                }
            }
            else if($status == 'Pending'){
                if($data->name == 'Makati'){
                    if($invoice_select == 'All'){
                        $invoice = invoice::where('book_type_id', $get_book_id)->get();
                        $book = 0;
                        foreach($invoice as $i){
                            $book += $i->pending;
                        }
                    }
                    else{
                        $book = invoice::where('book_type_id', $get_book_id)
                            ->where('ref_no_id', $invoice_select)->first();
                        $book = $book->pending;
                    }
                }
                else{
                    $book = pending_request::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->first();
                    $book = ($book) ? $book->pending : 0;
                }
            }
            else{
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('status', $status)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('status', $status)
                        ->where('book_type_id', $get_book_id)->where('invoice_ref_id', $invoice_select)->count();
                }
            }

            $book = ($book) ? $book : 0;
            return $book;
        })
        ->addColumn('wb_2', function($data) use($status, $invoice_select){
            $get_book = book_type::where('name', 'WB 2')->first();
            $get_book_id = $get_book->id;

            if($status == 'All'){
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)
                        ->where('invoice_ref_id', $invoice_select)->count();
                }
            }
            else if($status == 'Pending'){
                if($data->name == 'Makati'){
                    if($invoice_select == 'All'){
                        $invoice = invoice::where('book_type_id', $get_book_id)->get();
                        $book = 0;
                        foreach($invoice as $i){
                            $book += $i->pending;
                        }
                    }
                    else{
                        $book = invoice::where('book_type_id', $get_book_id)
                            ->where('ref_no_id', $invoice_select)->first();
                        $book = $book->pending;
                    }
                }
                else{
                    $book = pending_request::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->first();
                    $book = ($book) ? $book->pending : 0;
                }
            }
            else{
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('status', $status)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('status', $status)
                        ->where('book_type_id', $get_book_id)->where('invoice_ref_id', $invoice_select)->count();
                }
            }

            $book = ($book) ? $book : 0;
            return $book;
        })
        ->addColumn('kanji', function($data) use($status, $invoice_select){
            $get_book = book_type::where('name', 'Kanji')->first();
            $get_book_id = $get_book->id;

            if($status == 'All'){
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('book_type_id', $get_book_id)
                        ->where('invoice_ref_id', $invoice_select)->count();
                }
            }
            else if($status == 'Pending'){
                if($data->name == 'Makati'){
                    if($invoice_select == 'All'){
                        $invoice = invoice::where('book_type_id', $get_book_id)->get();
                        $book = 0;
                        foreach($invoice as $i){
                            $book += $i->pending;
                        }
                    }
                    else{
                        $book = invoice::where('book_type_id', $get_book_id)
                            ->where('ref_no_id', $invoice_select)->first();
                        $book = $book->pending;
                    }
                }
                else{
                    $book = pending_request::where('branch_id', $data->id)->where('book_type_id', $get_book_id)->first();
                    $book = ($book) ? $book->pending : 0;
                }
            }
            else{
                if($invoice_select == 'All'){
                    $book = books::where('branch_id', $data->id)->where('status', $status)->where('book_type_id', $get_book_id)->count();
                }
                else{
                    $book = books::where('branch_id', $data->id)->where('status', $status)
                        ->where('book_type_id', $get_book_id)->where('invoice_ref_id', $invoice_select)->count();
                }
            }
            
            $book = ($book) ? $book : 0;
            return $book;
        })
        ->make(true);
    }
}
