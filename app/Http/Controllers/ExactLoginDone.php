<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ExactLoginDone extends BaseController
{
    public function GetData() {

        return view('ExactDoneView', ['test' => 'You should be logged in.']);
    }
}
