<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pending_request;
use App\employee;
use App\books;
use App\release_books;
use App\branch;
use Auth;
use Yajra\Datatables\Datatables;

class releaseBooksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function get_branch(){
        $branch = pending_request::with('branch')->where('pending', '>', 0)->groupBy('branch_id')->get()->toArray();

        $array = [];
        foreach ($branch as $key => $value){
            $array[] = [
                'id' =>$value['branch_id'],
                'text' => $value['branch']['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function get_books(Request $request){
        $branch = $request->branch_id;
        $book = pending_request::with('book_type')->where('branch_id', $branch)->where('pending', '>', 0)->get()->toArray();
        $array = [];
        foreach ($book as $key => $value){
            $array[] = [
                'id' =>$value['book_type_id'],
                'text' => $value['book_type']['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function get_pending(Request $request){
        $book_type = $request->book_type;
        $get_branch = branch::where('name', 'Makati')->first();
        $branch = $get_branch->id;
        $pending_branch = $request->branch_id;

        $pending = pending_request::where('book_type_id', $book_type)->where('branch_id', $pending_branch)->first();

        $starting = books::where('branch_id', $branch)->where('book_type_id', $book_type)
                        ->where('status', 'Available')->orderBy('name')->first();

        $stocks = $starting->count();
                        
        $pending = ($pending) ? $pending->pending : 0;
        $starting = ($starting) ? $starting->name : 0;

        $output = array(
            'pending' => $pending,
            'starting' => $starting,
            'stocks' => $stocks
        );

        echo json_encode($output);
    }

    public function save_book_release(Request $request){
        $book_type = $request->release_book;
        $branch = $request->release_branch;
        $starting = $request->release_starting;
        $start = $request->release_start;
        $end = $request->release_end;

        $pending_request = pending_request::where('book_type_id', $book_type)->where('branch_id', $branch)->first();
        $pending_request->pending = $request->release_pending;
        $pending_request->save();

        for($x = $start; $x <= $end; $x++){
            $books = books::where('name', $x)->where('book_type_id', $book_type)->first();
            $books->branch_id = $branch;
            $books->save();
        }

        $release_books = new release_books;
        $release_books->p_request_id = $pending_request->id;
        $release_books->quantity = $request->release_quantity;
        $release_books->previous_pending = $request->release_previous_pending;
        $release_books->pending = $request->release_pending;
        $release_books->book_no_start = $start;
        $release_books->book_no_end = $end;
        $release_books->remarks = $request->release_remarks;
        $release_books->save();
    }

    public function view_release_books(){
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->name;


        $release_books = release_books::with('pending_request.book_type', 'pending_request.branch')->get();
        if($branch != 'Makati'){
            $release_books = $release_books->where('pending_request.branch.name', $branch);
        }

        return Datatables::of($release_books)
        ->addColumn('book_range', function($data){
            return $data->book_no_start . ' - ' . $data->book_no_end;
        })
        ->addColumn('action', function($data){
            return 'TEMP';
        })
        ->make(true);
    }
}
