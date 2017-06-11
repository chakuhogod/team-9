<?php

namespace App\Http\Controllers;

use App\Repositories\ExactRepository;
use Illuminate\Routing\Controller as BaseController;

class ExactBack extends BaseController
{
    public function GetData(ExactRepository $exact) {

        $sendback = file_get_contents("../app/Repositories/ExactSendBack.csv");
        $sales = file_get_contents("../app/Repositories/ExactSales.csv");
        $purchase = file_get_contents("../app/Repositories/ExactPurchase.csv");

        file_put_contents("../app/Repositories/ExactSendBack.csv","");
        file_put_contents("../app/Repositories/ExactSales.csv", "");
        file_put_contents("../app/Repositories/ExactPurchase.csv", "");

        if (!empty($sales)) {
            echo $sales;
            $exact->CreateSalesEntry($sales);
        }
        if (!empty($purchase)) {
            echo $purchase;
            $exact->CreatePurchaseEntry($purchase);
        }

        if (!empty($sendback)){
            header("Location: ".$sendback);
            exit;
        }

        return view('ExactBack', ['test' => 'Exact Online with Laravel!']);
    }
}
