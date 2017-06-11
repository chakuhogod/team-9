<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class ExactSyncController extends BaseController
{
    public function GetData() {

        echo 'Currently syncing to exact online...';

        $items = mb_split("\r", file_get_contents("../app/Repositories/ExactSync.csv"));

        echo "<br>";

        $filename = "../app/Repositories/ExactSync.csv";
        $file = file($filename);
        unset($file[0]);
        file_put_contents($filename, $file);

        foreach ($items as $value)
        {
            $newvalue = trim(preg_replace('/\s\s+/', ' ', $value));

            if(0 === strpos($newvalue, 'S'))
            {
                $amount = substr($newvalue, 1,strlen($newvalue) - 1);

                echo "<br>Sale order found: ".$amount;

                Header("Location: http://localhost:8000/ExactAuth?Sales=".$amount."&Sendback=http://localhost:8000/ExactSync");
                exit;
            }
            if(0 === strpos($newvalue, 'P'))
            {
                $amount = substr($newvalue, 1,strlen($newvalue) - 1);

                echo "<br>Purchase order found: ".$amount;

                Header("Location: http://localhost:8000/ExactAuth?Purchase=".$amount."&Sendback=http://localhost:8000/ExactSync");
                exit;
            }
        }

        //return view('ExactBack', ['test' => 'Works!']);
    }
}
