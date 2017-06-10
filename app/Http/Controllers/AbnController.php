<?php

namespace App\Http\Controllers;


use resources\services\ABN\AbnService;

class AbnController extends Controller
{
    public function getAccounts(AbnService $abn)
    {
        $oauth = $abn->getAccesToken();

        $accounts = $abn->getTransactions($oauth);

        /*return view('abn');*/
        return null;
    }
}