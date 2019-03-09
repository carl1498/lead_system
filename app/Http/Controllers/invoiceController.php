<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\invoice;
use App\reference_no;
use App\book_type;
use App\add_books;
use App\books;
use App\branch;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class invoiceController extends Controller
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
        return view('pages.invoice');
    }

    public function view(Request $request){
        $invoice = invoice::with('reference_no')->groupBy('ref_no_id')->get();
        $invoice_select = $request->invoice_select;

        return Datatables::of($invoice)
        ->addColumn('book_1', function($data) use($invoice_select){
            $get_book = book_type::where('name', 'Book 1')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if($invoice_select == 'Quantity'){
                return $book->quantity;
            }
            else if($invoice_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('wb_1', function($data) use($invoice_select){
            $get_book = book_type::where('name', 'WB 1')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if($invoice_select == 'Quantity'){
                return $book->quantity;
            }
            else if($invoice_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('book_2', function($data) use($invoice_select){
            $get_book = book_type::where('name', 'Book 2')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if($invoice_select == 'Quantity'){
                return $book->quantity;
            }
            else if($invoice_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('wb_2', function($data) use($invoice_select){
            $get_book = book_type::where('name', 'WB 2')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if($invoice_select == 'Quantity'){
                return $book->quantity;
            }
            else if($invoice_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('kanji', function($data) use($invoice_select){
            $get_book = book_type::where('name', 'Kanji')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if($invoice_select == 'Quantity'){
                return $book->quantity;
            }
            else if($invoice_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('action', function($data) use($invoice_select){
            $html = '
                    <button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_invoice" id="'.$data->ref_no_id.'"><i class="fa fa-trash-alt"></i></button>';
            
            return $html;
        })
        ->make(true);
    }

    public function view_add_books(){
        $add_books = add_books::with('reference_no', 'book_type')->get();

        return Datatables::of($add_books)
        ->addColumn('book_range', function($data){
            return $data->book_no_start . ' - ' . $data->book_no_end;
        })
        ->addColumn('action', function($data){
            return 'TEMP';
        })
        ->make(true);
    }

    public function save_invoice(Request $request){
        //FOR REFERENCE NO -- START
        $year = Carbon::now()->year;
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('d');
        $get_invoice_id = reference_no::orderBy('id','desc')->first();

        $ref_no = new reference_no;
        if($get_invoice_id != null){
            $ref_no->lead_ref_no = $year . $month . $day . $get_invoice_id->id+1;
        }
        else{
            $ref_no->lead_ref_no = $year . $month . $day . 1;
        }

        $ref_no->invoice_ref_no = $request->invoice_ref_no;
        $ref_no->save();
        //FOR REFERENCE NO -- END

        //FOR INVOICE -- START
        $ref_no = reference_no::where('invoice_ref_no', $request->invoice_ref_no)->first();
        $ref_id = $ref_no->id;

        $quantity = array($request->book_1, $request->wb_1, $request->book_2, $request->wb_2, $request->kanji);
        $book_types = array('Book 1', 'WB 1', 'Book 2', 'WB 2', 'Kanji');

        for($x = 0; $x < 5; $x++){
            $invoice = new invoice;
            $invoice->ref_no_id = $ref_id;
            $get_book_type_id = book_type::where('name', $book_types[$x])->first();
            $invoice->book_type_id = $get_book_type_id->id;
            if($quantity[$x]){
                $invoice->quantity = $quantity[$x];
                $invoice->pending = $quantity[$x];
            }else{
                $invoice->quantity = 0;
                $invoice->pending = 0;
            }
            $invoice->save();
        }
        //FOR INVOICE -- END
    }

    public function invoice_all(Request $request){
        $invoice = invoice::with('reference_no')->where('pending', '>', 0)->groupBy('ref_no_id')->get()->toArray();
        $array = [];
        foreach ($invoice as $key => $value){
            $array[] = [
                'id' => $value['ref_no_id'],
                'text' => $value['reference_no']['invoice_ref_no']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function book_all(Request $request){
        $invoice = $request->invoice_id;
        $book = invoice::with('book_type')->where('ref_no_id', $invoice)->where('pending', '>', 0)->get()->toArray();
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
        $invoice = $request->invoice_id;
        $book_type = $request->book_type;

        $pending = invoice::where('ref_no_id', $invoice)->where('book_type_id', $book_type)->first();

        return $pending->pending;
    }

    public function get_starting(Request $request){
        $book_type = $request->book_type;
        $last_book = books::where('book_type_id', $book_type)->orderBy('name', 'desc')->first();

        if($last_book){
            return $last_book->name + 1;
        }
        else{
            return 1;
        }
    }

    public function save_books(Request $request){
        $start = $request->start;
        $end = $request->end;
        $ref_no = $request->invoice_add_book;
        $book_type = $request->book_type_add_book;
        $makati = branch::where('name', 'Makati')->first();

        $add_book = new add_books;
        $add_book->invoice_ref_id = $ref_no;
        $add_book->book_type_id = $book_type;
        $add_book->quantity = $request->quantity;
        $add_book->previous_pending = $request->previous_pending;
        $add_book->pending = $request->pending;
        $add_book->book_no_start = $start;
        $add_book->book_no_end = $end;
        $add_book->remarks = $request->remarks;
        $add_book->save();

        for($x = $start; $x <= $end; $x++){
            $books = new books;
            $books->name = $x;
            $books->book_type_id = $request->book_type_add_book;
            $books->invoice_ref_id = $ref_no;
            $books->branch_id = $makati->id;
            $books->status = 'Available';
            $books->save();
        }

        $pending = invoice::where('ref_no_id', $ref_no)->where('book_type_id', $book_type)->first();
        $pending->pending = $request->pending;
        $pending->save();
    }
}
