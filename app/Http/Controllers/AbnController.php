<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use resources\services\ABN\AbnService;

class AbnController extends Controller
{
    public function getAccounts(AbnService $abn)
    {
        $oauth = $abn->getAccesToken();

        $accounts = $abn->getTransactionsWithKeyAmount($oauth);

        $transaction = $abn->getBalance($oauth);

        $acountInfo = $abn->getAccountInfo($oauth);


        return new JsonResponse($accounts);
    }
    public function getBalance(AbnService $abn)
    {
        $oauth = $abn->getAccesToken();

        $acountInfo = $abn->getAccountInfo($oauth);


        return new JsonResponse($acountInfo);
    }
}