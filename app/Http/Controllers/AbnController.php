<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use resources\services\ABN\AbnService;

class AbnController extends Controller
{
    public function getTransactions(AbnService $abn, Request $request)
    {
        $oauth = $abn->getAccesToken();

        return new JsonResponse($abn->getTransactions($oauth, $request->get('account'), $request->get('dateFrom'), $request->get('dateTo')));

    }
}