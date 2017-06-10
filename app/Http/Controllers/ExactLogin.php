<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ExactLogin extends BaseController
{
    public function GetData() {

        $connection = new \Picqer\Financials\Exact\Connection();
        $connection->setRedirectUrl('http://localhost:8000/ExactLoginDone'); // Same as entered online in the App Center
        $connection->setExactClientId('9577d765-5430-45e5-9d48-a19217462344');
        $connection->setExactClientSecret('LtV8RW4nDd2Q');
        $connection->redirectForAuthorization();

        return view('ExactDoneView', ['test' => 'You should be logged in.']);
    }
}
