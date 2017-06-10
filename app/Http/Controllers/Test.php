<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class Test extends BaseController
{
    public function GetData() {

        return view('ExactView', ['test' => 'Your memes work.']);
    }
}
