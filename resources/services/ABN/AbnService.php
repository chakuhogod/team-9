<?php

namespace resources\services\ABN;

class AbnService
{
    /**
     * Returns Oauth Key
     *
     * @return mixed
     */
    public function getAccesToken()
    {
        $url = "https://api-sandbox.abnamro.com/v1/oauth/token";

        $data = 'grant_type=client_credentials&scope=ais&accountNumber='. env('ABN_ACCOUNT_NUMBER');

        $options = [
            'authorization :Basic '.base64_encode(env('ABN_KEY').':'.env('ABN_SECRET')),
            'Content-Type:application/x-www-form-urlencoded',
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_FRESH_CONNECT,1);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $options);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$data);

        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HEADER,1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($curl);

        curl_close($curl);

        $json = explode('{', $json)[1];

        return json_decode("{".$json, true);
    }

    /**
     * Gets Transactions
     *
     * @param $token
     * @param null $accountNumber
     * @return mixed
     */

    public function getTransactions($token, $accountNumber = null)
    {
        if(!$accountNumber)$accountNumber = env('ABN_ACCOUNT_NUMBER');

        $url = "https://api-sandbox.abnamro.com/v1/ais/transactions?accountNumber=".$accountNumber;

        $options = ["Authorization: Bearer ".$token['access_token']];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_FRESH_CONNECT,1);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $options);

        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_HEADER,1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($curl);

        curl_close($curl);

        $json = explode('{', $json)[1];

        return json_decode("{".$json, true);
    }

    
}