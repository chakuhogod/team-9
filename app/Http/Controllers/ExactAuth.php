<?php

namespace App\Http\Controllers;

use App\Repositories\ExactRepository;
use GuzzleHttp;
use Illuminate\Config\Repository;
use Illuminate\Routing\Controller as BaseController;

class ExactAuth extends BaseController
{
    public function GetData(ExactRepository $exact) {

        file_put_contents("../app/Repositories/ExactPurchase.csv", "");
        file_put_contents("../app/Repositories/ExactSales.csv", "");
        file_put_contents("../app/Repositories/storage.json", "[]");

        if (request()->has('Sales')) {

            file_put_contents("../app/Repositories/ExactSales.csv", $_GET["Sales"]);

            $exact->CreateSalesEntry($_GET["Sales"]);
        }

        if (request()->has('Purchase')) {

            file_put_contents("../app/Repositories/ExactPurchase.csv", $_GET["Purchase"]);

            $exact->CreateSalesEntry($_GET["Purchase"]);
        }

        return view('ExactAuth', ['test' => 'Exact Online with Laravel!']);
    }
}
