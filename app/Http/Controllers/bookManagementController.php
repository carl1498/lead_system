<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\book_type;
use App\branch;

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

        return view('pages.book_management', compact('book_type', 'branch'));
    }
}
