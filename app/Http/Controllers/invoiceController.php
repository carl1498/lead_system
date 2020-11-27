<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\LogsTraits;
use App\invoice;
use App\reference_no;
use App\book_type;
use App\add_books;
use App\books;
use App\branch;
use Carbon\Carbon;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;

class invoiceController extends Controller
{
    use LogsTraits;

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
        $this->page_access_log(Auth::user()->emp_id, 'Invoice', 'Visit');

        $book_type = book_type::all();
        $invoice = reference_no::all();

        return view('pages.invoice', compact('book_type', 'invoice'));
    }

    public function view(Request $request){
        $this->page_access_log(Auth::user()->emp_id, 'Invoice', 'Visit');

        $invoice = invoice::with('reference_no')->groupBy('ref_no_id')->get();
        $type_select = $request->type_select;

        return Datatables::of($invoice)
        ->addColumn('book_1', function($data) use($type_select){
            $get_book = book_type::where('name', 'Minna 1 (Student)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('wb_1', function($data) use($type_select){
            $get_book = book_type::where('name', 'WB 1 (Student)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('book_2', function($data) use($type_select){
            $get_book = book_type::where('name', 'Minna 2 (Student)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('wb_2', function($data) use($type_select){
            $get_book = book_type::where('name', 'WB 2 (Student)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('kanji', function($data) use($type_select){
            $get_book = book_type::where('name', 'Kanji (Student)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('book_1_ssw', function($data) use($type_select){
            $get_book = book_type::where('name', 'Minna 1 (SSW)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('wb_1_ssw', function($data) use($type_select){
            $get_book = book_type::where('name', 'WB 1 (SSW)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('book_2_ssw', function($data) use($type_select){
            $get_book = book_type::where('name', 'Minna 2 (SSW)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('wb_2_ssw', function($data) use($type_select){
            $get_book = book_type::where('name', 'WB 2 (SSW)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('kanji_ssw', function($data) use($type_select){
            $get_book = book_type::where('name', 'Kanji (SSW)')->first();
            $get_book_id = $get_book->id;
            $book = $data->where('ref_no_id', $data->ref_no_id)->where('book_type_id', $get_book_id)->first();
            if((empty($book))){return 0;}
            if($type_select == 'Quantity'){
                return $book->quantity;
            }
            else if($type_select == 'Pending'){
                return $book->pending;
            }
        })
        ->addColumn('action', function($data) use($type_select){
            $html = '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_invoice" id="'.$data->ref_no_id.'"><i class="fa fa-trash-alt"></i></button>';
            
            return $html;
        })
        ->make(true);
    }

    public function view_add_books(Request $request){
        $book_type_select = $request->book_type_select;
        $invoice_select = $request->invoice_select;

        $add_books = add_books::with('reference_no', 'book_type')->get();

        if($book_type_select != 'All'){
            $add_books = $add_books->where('book_type_id', $book_type_select);
        }

        if($invoice_select != 'All'){
            $add_books = $add_books->where('invoice_ref_id', $invoice_select);
        }

        return Datatables::of($add_books)
        ->addColumn('book_range', function($data){
            return $data->book_no_start . ' - ' . $data->book_no_end;
        })
        ->addColumn('action', function($data){
            $html = '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_add_book" id="'.$data->id.'"><i class="fa fa-trash-alt"></i></button>';
            
            return $html;
        })
        ->make(true);
    }

    public function save_invoice(Request $request){
        //FOR REFERENCE NO -- START
        $year = Carbon::now()->year;
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('d');
        $get_invoice_id = reference_no::orderBy('id','desc')->first();
        $check_duplicate = reference_no::where('invoice_ref_no', $request->invoice_ref_no)->first();
        if(isset($check_duplicate)){
            return 'false';
        }

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

        $quantity = array($request->book_1, $request->wb_1, $request->book_2, $request->wb_2, $request->kanji,
        $request->book_1_ssw, $request->wb_1_ssw, $request->book_2_ssw, $request->wb_2_ssw, $request->kanji_ssw);
        
        $book_types = array('Minna 1 (Student)', 'WB 1 (Student)', 'Minna 2 (Student)', 'WB 2 (Student)', 'Kanji (Student)',
        'Minna 1 (SSW)', 'WB 1 (SSW)', 'Minna 2 (SSW)', 'WB 2 (SSW)', 'Kanji (SSW)');

        for($x = 0; $x < count($book_types); $x++){
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
        $invoice = invoice::with('reference_no')->where('pending', '>', 0)
            ->whereHas('reference_no', function($query) use ($request) {
                $query->where('invoice_ref_no', 'LIKE', '%'.$request->name.'%');
            })
            ->groupBy('ref_no_id')->get()->toArray();

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
        $book = invoice::with('book_type')->where('ref_no_id', $invoice)
            ->whereHas('book_type', function($query) use ($request) {
                $query->where('description', 'LIKE', '%'.$request->name.'%');
            })
            ->where('pending', '>', 0)->get()->toArray();

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

    public function delete_invoice(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }
        $id = $request->id;
        $invoice = invoice::where('ref_no_id', $id)->delete();
        $reference_no = reference_no::find($id)->delete();
    }
    
    public function delete_add_book(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }
        $id = $request->id;
        
        $add_book = add_books::find($id);
        $start = $add_book->book_no_start;
        $end = $add_book->book_no_end;
        $book_type = $add_book->book_type_id;
        $ref_no = $add_book->invoice_ref_id;

        for($x = $start; $x <= $end; $x++){
            $book = books::with('branch')->where('name', $x)->where('book_type_id', $book_type)->first();
            if($book->status != 'Available' || $book->branch->name != 'Makati'){
                return 1;
            }
        }

        for($x = $start; $x <= $end; $x++){
            $book = books::where('name', $x)->where('book_type_id', $book_type)->first();
            $book->delete();
        }

        $pending = invoice::where('ref_no_id', $ref_no)->where('book_type_id', $book_type)->first();
        $pending->pending += $add_book->quantity;
        $pending->save();

        $add_book->delete();
    }
}
