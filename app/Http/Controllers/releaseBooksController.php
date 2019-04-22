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

    public function get_branch(Request $request){
        $branch = pending_request::with('branch')->where('pending', '>', 0)
            ->whereHas('branch', function($query) use ($request) {
                $query->where('name', 'LIKE', '%'.$request->name.'%');
            })    
            ->groupBy('branch_id')->get();

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
        $book = pending_request::with('book_type')->where('branch_id', $branch)
            ->whereHas('book_type', function($query) use ($request) {
                $query->where('description', 'LIKE', '%'.$request->name.'%');
            })
            ->where('pending', '>', 0)->get();
        $array = [];
        foreach ($book as $key => $value){
            $array[] = [
                'id' =>$value['book_type_id'],
                'text' => $value['book_type']['description']
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

        $stocks = books::where('branch_id', $branch)->where('book_type_id', $book_type)
                    ->where('status', 'Available')->count();
                        
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

        //checker;
        for($x = $start; $x <= $end; $x++){
            $books = books::with('branch')->where('name', $x)->where('book_type_id', $book_type)->first();
            if($books->branch->name != 'Makati' || $books->status == 'Lost' || $books->status == 'Released'){
                return $books->name;
            }
        }

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

    public function view_release_books(Request $request){
        $book_type_select = $request->book_type_select;
        $branch_select = $request->branch_select;
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->name;


        $release_books = release_books::with('pending_request.book_type', 'pending_request.branch')->get();
        if($branch != 'Makati'){
            $release_books = $release_books->where('pending_request.branch.name', $branch);
        }

        if($book_type_select != 'All'){
            $release_books = $release_books->where('pending_request.book_type_id', $book_type_select);
        }

        if($branch_select != 'All'){
            $release_books = $release_books->where('pending_request.branch_id', $branch_select);
        }

        return Datatables::of($release_books)
        ->editColumn('status', function($data){
            if($data->status == 'Pending'){
                $status = 'warning';
            }else if($data->status == 'Received'){
                $status = 'success';
            }else if($data->status == 'Returned'){
                $status = 'danger';
            }

            return '<span class="label label-'.$status.'">'.$data->status.'</span>';
        })
        ->addColumn('book_range', function($data){
            return $data->book_no_start . ' - ' . $data->book_no_end;
        })
        ->addColumn('action', function($data){
            $user = Auth::user()->emp_id;
            $user = employee::with('role')->where('id', $user)->first();
            $html = '';

            if($data->status == 'Pending'){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Received" class="btn btn-success btn-xs receive_release" id="'.$data->id.'"><i class="fa fa-thumbs-up"></i></button>&nbsp;';
            }

            if(canAccessAll() || $user->role->name == 'Language Head'){
                if($data->status == 'Received'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Pending" class="btn btn-warning btn-xs pending_release" id="'.$data->id.'"><i class="fa fa-minus"></i></button>&nbsp;';
                }
                if($data->status == 'Pending'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Return" class="btn btn-danger btn-xs return_release" id="'.$data->id.'"><i class="fa fa-undo"></i></button>&nbsp;';
                }
            }
            
            return $html;
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    public function received_book_release(Request $request){
        $id = $request->id;
        $received_release = release_books::find($id);
        $received_release->status = 'Received';
        $received_release->save();
    }

    public function pending_book_release(Request $request){
        $id = $request->id;
        $pending_release = release_books::find($id);
        $pending_release->status = 'Pending';
        $pending_release->save();
    }

    public function return_book_release(Request $request){
        $id = $request->id;
        $return_release = release_books::find($id);
        $current_pending = pending_request::where('id', $return_release->p_request_id)->first();
        $branch = branch::find($current_pending->branch_id);
        $makati_branch = branch::where('name', 'Makati')->first();
        $counter = true;

        for($x = $return_release->book_no_start; $x <= $return_release->book_no_end; $x++){
            $book = books::where('branch_id', $branch->id)->where('name', $x)->first();
            if($book->status != 'Available'){
                $counter = false;
                return 1;
            }
        }

        for($x = $return_release->book_no_start; $x <= $return_release->book_no_end; $x++){
            $book = books::where('branch_id', $branch->id)->where('name', $x)->first();
            $book->branch_id = $makati_branch->id;
            $book->save();
        }

        $return_release->status = 'Returned';
        $return_release->save();

        $return = new release_books;
        $return->p_request_id = $return_release->p_request_id;
        $return->previous_pending = $current_pending->pending;
        $return->quantity = -1 * abs($return_release->quantity);
        $return->pending = $current_pending->pending - $return->quantity;
        $return->book_no_start = $return_release->book_no_start;
        $return->book_no_end = $return_release->book_no_end;
        $return->remarks = $return_release->remarks;
        $return->status = 'Returned';
        $return->remarks = 'Return Release ID: '.$id;
        $return->save();

        $current_pending->pending = $current_pending->pending - $return->quantity;
        $current_pending->save();
    }
}
