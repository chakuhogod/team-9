<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $htmlTitle  ='Dashboard';
        return view('dashboard',compact('htmlTitle'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function bookkeeping(Request $request)
    {
        $htmlTitle  ='Bookkeeping';
        $search = $request->input('search');
        return view('bookkeeping',compact('htmlTitle', 'search'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function charts()
    {
        $htmlTitle  ='Charts';
        return view('charts',compact('htmlTitle'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $htmlTitle  ='Dashboard';
        return view('dashboard',compact('htmlTitle'));
    }
}
