<?php

namespace App\Http\Controllers;


use resources\Services\ABN\AbnService;

class AbnController extends Controller
{
    public function getAccounts(AbnService $service)
    {
        $oauth = $service->getAccesToken();

        /*return view('abn');*/
    }
}