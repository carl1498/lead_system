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
        $branch_select = $request->branch_select;
        $get_branch = employee::with('branch')->where('id', Auth::user()->emp_id)->first();
        $branch = $get_branch->branch->name;

        $request_books = request_books::with('pending_request.book_type', 'pending_request.branch')->get();
        
        if($branch != 'Makati'){
            $request_books = $request_books->where('pending_request.branch.name', $branch);
        }

        if($book_type_select != 'All'){
            $request_books = $request_books->where('pending_request.book_type_id', $book_type_select);
        }

        if($branch_select != 'All'){
            $request_books = $request_books->where('pending_request.branch_id', $branch_select);
        }
        
        return Datatables::of($request_books)
        ->editColumn('status', function($data){
            if($data->status == 'Pending'){
                $status = 'warning';
            }else if($data->status == 'Approved'){
                $status = 'info';
            }else if($data->status == 'Delivered'){
                $status = 'success';
            }else if($data->status == 'Cancelled'){
                $status = 'danger';
            }

            return '<span class="label label-'.$status.'">'.$data->status.'</span>';
        })
        ->addColumn('action', function($data){
            $user = Auth::user()->emp_id;
            $user = employee::with('role')->where('id', $user)->first();
            $html = '';

            if(canAccessAll() || $user->role->name == 'Language Head'){
                if($data->status == 'Pending'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Approve" class="btn btn-info btn-xs approve_request" id="'.$data->id.'"><i class="fa fa-check"></i></button>';
                }

                if($data->status != 'Delivered' && $data->status != 'Cancelled'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delivered" class="btn btn-success btn-xs deliver_request" id="'.$data->id.'"><i class="fa fa-thumbs-up"></i></button>';
                }
                
                if($data->status == 'Approved' || $data->status == 'Delivered'){
                    $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Pending" class="btn btn-warning btn-xs pending_request" id="'.$data->id.'"><i class="fa fa-minus"></i></button>';
                }
            }

            if($data->status == 'Pending'){
                $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Cancel" class="btn btn-danger btn-xs cancel_request" id="'.$data->id.'"><i class="fa fa-times"></i></button>';
            }

            return $html;
        })
        ->rawColumns(['status', 'action'])
        ->make(true);
    }

    public function approve_book_request(Request $request){
        $id = $request->id;
        $approve_request = request_books::find($id);
        $approve_request->status = 'Approved';
        $approve_request->save();
    }

    public function delivered_book_request(Request $request){
        $id = $request->id;
        $delivered_request = request_books::find($id);
        $delivered_request->status = 'Delivered';
        $delivered_request->save();
    }

    public function pending_book_request(Request $request){
        $id = $request->id;
        $pending_request = request_books::find($id);
        $pending_request->status = 'Pending';
        $pending_request->save();
    }

    public function cancel_book_request(Request $request){
        $id = $request->id;
        $cancel_request = request_books::find($id);
        $current_pending = pending_request::where('id', $cancel_request->p_request_id)->first();

        if($current_pending->pending < $cancel_request->quantity){
            return 1;
        }
        $cancel_request->status = 'Cancelled';
        $cancel_request->save();

        $cancel = new request_books;
        $cancel->p_request_id = $cancel_request->p_request_id;
        $cancel->previous_pending = $current_pending->pending;
        $cancel->quantity = -1 * abs($cancel_request->quantity);
        $cancel->pending = $current_pending->pending + $cancel->quantity;
        $cancel->status = 'Cancelled';
        $cancel->remarks = 'Cancelled Request ID: '.$id;
        $cancel->save();
        
        $current_pending->pending = $current_pending->pending + $cancel->quantity;
        $current_pending->save();
    }
}
