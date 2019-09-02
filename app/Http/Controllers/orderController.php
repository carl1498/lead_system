<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\order;
use App\order_type;
use App\client;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Redirect;

class orderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $order = order::all();
        $order_type = order_type::all();
        $client = client::all();
        
        return view('pages.order', compact('order', 'order_type', 'client'));
    }

    public function view_order(Request $request){
        $order_type = $request->current_tab;

        $order = order::with('order_type', 'client')
            ->whereHas('order_type', function($query) use($order_type){
                $query->where('name', $order_type);
            })->get();

        return Datatables::of($order)
        ->addColumn('time', function($data){
            return $data->start_time . ' - ' . $data->end_time;
        })
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_order" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_order" id="'.$data->id.'"><i class="fa fa-trash"></i></button>&nbsp;';
            
            return $html;
        })
        ->make(true);
    }

    public function get_order(Request $request){
        return order::find($request->id);
    }

    public function clientAll(Request $request){
        $client = client::where('name', 'LIKE', '%'.$request->name.'%')->get();

        $array = [];
        foreach ($client as $key => $value){
            $array[] = [
                'id' => $value['id'],
                'text' => $value['name']
            ];
        }
        return json_encode(['results' => $array]);
    }

    public function save_order(Request $request){
        $id = $request->id;
        $add_edit = $request->add_edit;

        $order = ($add_edit == 'add') ? new order : order::find($id);

        $order->order_type_id = $request->order_type;
        $order->client_id = $request->client;
        $order->no_of_orders = $request->order;
        $order->no_of_hires = $request->hires;
        $order->interview_date = $request->interview_date;
        $order->start_time = $request->start_time;
        $order->end_time = $request->end_time;
        $order->remarks = $request->remarks;
        $order->save();
    }

    public function delete_order(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $order = order::find($request->id);
        $order->delete();
    }
}
