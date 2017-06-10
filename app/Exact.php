<?php

namespace App;

class ExactLogin
{
    static $connection = new \Picqer\Financials\Exact\Connection();

    public function GetData() {

        $connection = new \Picqer\Financials\Exact\Connection();
        $connection->setRedirectUrl('http://localhost:8000/ExactLoginDone'); // Same as entered online in the App Center
        $connection->setExactClientId('9577d765-5430-45e5-9d48-a19217462344');
        $connection->setExactClientSecret('LtV8RW4nDd2Q');
        $connection->redirectForAuthorization();



        return view('ExactView', ['test' => 'BAM redirected mafaka']);
    }
}
