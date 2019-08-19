<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\client;
use App\client_bank;
use App\client_company_type;
use App\client_pic;
use Auth;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Redirect;

class clientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $company_type = client_company_type::all();

        return view('pages.client', compact('company_type'));
    }

    public function view_client(Request $request){
        $client_type = $request->current_tab;

        $client = client::with('client_company_type')
        ->whereHas('client_company_type', function($query) use($client_type){
            $query->where('name', $client_type);
        })->get();

        return Datatables::of($client)
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_client" id="'.$data->id.'"><i class="fa fa-pen"></i></button>&nbsp;';
            
            return $html;
        })
        ->make(true);
    }

    public function save_client(Request $request){
        $id = $request->id;
        $add_edit = $request->add_edit;
        info($id);
        info($add_edit);

        $client = ($add_edit == 'add') ? new client : client::find($id);

        $client->client_company_type_id = $request->company_type;
        $client->name = $request->client_name;
        $client->address = $request->client_address;
        $client->contact = $request->client_contact;
        $client->email = $request->client_email;
        $client->save();
    }

    public function get_client(Request $request){
        return client::find($request->id);
    }
}
