<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\client;
use App\client_bank;
use App\client_company_type;
use App\client_pic;
use App\industries;
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
        $industries = industries::whereNotIn('id', [8])->get();

        return view('pages.client', compact('company_type', 'industries'));
    }

    public function view_client(Request $request){
        $client_type = $request->current_tab;

        $client = client::with('client_company_type', 'industry')
        ->whereHas('client_company_type', function($query) use($client_type){
            $query->where('name', $client_type);
        })->get();

        return Datatables::of($client)
        ->addColumn('action', function($data){
            $html = '';

            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_client" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Person in Charge" class="btn btn-warning btn-xs pic" id="'.$data->id.'"><i class="fa fa-id-card"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Bank" class="btn btn-info btn-xs bank" id="'.$data->id.'"><i class="fa fa-university"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_client" id="'.$data->id.'"><i class="fa fa-trash"></i></button>';
            
            return $html;
        })
        ->make(true);
    }

    public function view_client_pic(Request $request){
        $client_id = $request->id;

        $client_pic = client_pic::where('client_id', $client_id);

        return Datatables::of($client_pic)
        ->addColumn('action', function($data){
            $html = '';
            
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Edit" class="btn btn-primary btn-xs edit_pic" id="'.$data->id.'"><i class="fa fa-pen"></i></button>';
            $html .= '<button data-container="body" data-toggle="tooltip" data-placement="left" title="Delete" class="btn btn-danger btn-xs delete_pic" id="'.$data->id.'"><i class="fa fa-trash"></i></button>';

            return $html;
        })
        ->make(true);
    }

    public function save_client(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $id = $request->id;
        $add_edit = $request->add_edit;

        $client = ($add_edit == 'add') ? new client : client::find($id);

        $client->client_company_type_id = $request->company_type;
        $client->name = $request->client_name;
        $client->address = $request->client_address;
        $client->contact = $request->client_contact;
        $client->email = $request->client_email;
        $client->ind_id = $request->industry;
        $client->url = $request->client_url;
        $client->save();
    }

    public function save_client_pic(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $id = $request->p_id;
        $client_id = $request->p_client_id;
        $add_edit = $request->p_add_edit;

        $client_pic = ($add_edit == 'add') ? new client_pic : client_pic::find($id);

        $client_pic->name = $request->p_name;
        $client_pic->position = $request->p_position;
        $client_pic->contact = $request->p_contact;
        $client_pic->email = $request->p_email;
        $client_pic->client_id = $client_id;
        $client_pic->save();

        return $client_id;
    }

    public function save_client_bank(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $id = $request->b_id;
        $client_id = $request->b_client_id;
        $add_edit = $request->b_add_edit;

        $client_bank = ($add_edit == 'add') ? new client_bank : client_bank::find($id);
        
        $client_bank->bank_name = $request->bank_name;
        $client_bank->swift_code = $request->swift_code;
        $client_bank->branch_name = $request->bank_branch_name;
        $client_bank->address = $request->bank_address;
        $client_bank->account_name = $request->account_name;
        $client_bank->account_number = $request->account_number;
        $client_bank->contact = $request->bank_contact;
        $client_bank->client_id = $request->b_client_id;
        $client_bank->save();
    }

    public function get_client(Request $request){
        return client::find($request->id);
    }

    public function get_client_pic(Request $request){
        return client::with('pic')->where('id', $request->id)->first();
    }

    public function get_pic(Request $request){
        return client_pic::find($request->id);
    }

    public function get_bank(Request $request){
        $client = client::find($request->id);
        $client_bank = (client_bank::where('client_id', $request->id)->first()) ? client_bank::where('client_id', $request->id)->first() : 'false';


        $output = array(
            'client' => $client,
            'client_bank' => $client_bank,
        );
        
        echo json_encode($output);
    }

    public function delete_client(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $client = client::find($request->id);
        $client->delete();
    }

    public function delete_pic(Request $request){
        if(!Hash::check($request->password, Auth::user()->password)){
            Auth::logout();
            return \Redirect::to('/');
        }

        $client_pic = client_pic::find($request->id);
        $client_id = $client_pic->client_id;
        $client_pic->delete();

        return $client_id;
    }
}
