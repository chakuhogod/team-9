<?php

namespace App\Http\Controllers;

class TransactionController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Load a transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function viewTransaction($key)
    {
        return view('transaction.view');
    }

}