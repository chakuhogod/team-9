<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use resources\services\ABN\AbnService;

class AbnController extends Controller
{
    public function getAccounts(AbnService $abn)
    {
        $oauth = $abn->getAccesToken();

        $accounts = $abn->getTransactions($oauth);

        $transaction = $abn->getBalance($oauth);

        $acountInfo = $abn->getAccountInfo($oauth);


        return new JsonResponse($accounts);
    }
}