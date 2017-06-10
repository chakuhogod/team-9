<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ExactAddSale extends BaseController
{
    public function GetData() {

        return view('ExactAddSaleView', ['test' => 'I might have done stuff']);
    }
}
