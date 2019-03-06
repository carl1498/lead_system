<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\invoice;
use App\reference_no;
use App\book_type;
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
        $test = $request->invoice_select;

        return Datatables::of($invoice)
        ->addColumn('book_1', function($data){
            $get_book = book_type::where('name', 'Book 1')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            /*if($request->invoice_select == 'Quantity'){
                return $book->quantity;
            }
            else if($request->invoice_select == 'Pending'){
                return $book->pending;
            }*/
            return $test;
        })
        ->addColumn('wb_1', function($data){
            $get_book = book_type::where('name', 'WB 1')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            return $book->quantity;
        })
        ->addColumn('book_2', function($data){
            $get_book = book_type::where('name', 'Book 2')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            return $book->quantity;
        })
        ->addColumn('wb_2', function($data){
            $get_book = book_type::where('name', 'WB 2')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            return $book->quantity;
        })
        ->addColumn('kanji', function($data){
            $get_book = book_type::where('name', 'Kanji')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            return $book->quantity;
        })
        ->addColumn('action', function($data){
            return "TEMP";
        })
        ->make(true);
    }

    public function save_invoice(Request $request){
        //FOR REFERENCE NO -- START
        $year = Carbon::now()->year;
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('d');
        $get_invoice_id = reference_no::first();

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
            $invoice->quantity = $quantity[$x];
            $invoice->pending = $quantity[$x];
            $invoice->save();
        }
        //FOR INVOICE -- END
    }
}
