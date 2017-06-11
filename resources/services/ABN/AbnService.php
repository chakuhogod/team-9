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
        $options = [
            'authorization :Basic '.base64_encode(env('ABN_KEY').':'.env('ABN_SECRET')),
            'Content-Type:application/x-www-form-urlencoded',
        ];

        $json = $this->curl('POST', "https://api-sandbox.abnamro.com/v1/oauth/token", $options, 'grant_type=client_credentials&scope=ais&accountNumber='. env('ABN_ACCOUNT_NUMBER') );

        $json = explode('{', $json)[1];

        return json_decode("{".$json, true);
    }

    /**
     * Gets Transactions
     *
     * @param  array $token
     * @param  array $data
     * @return mixed
     */

    public function getTransactions(array $token, array $data )
    {
        $accountNumber = $data['accountNumber'];
        $dateFrom      = $data['dataForm'];
        $dateTo        = $data['dateTo'];

        if(!$accountNumber)$accountNumber = env('ABN_ACCOUNT_NUMBER');
        $next = true;
        $nextPage = Null;

        $transactions = [];

        while ($next)
        {

            if(empty($nextPage))
            {
                $url = "https://api-sandbox.abnamro.com/v1/ais/transactions?accountNumber=".$accountNumber."&bookDateFrom='.$dateFrom.'&bookDateTo='.$dateTo.'";
            }
            else
            {
                $url = "https://api-sandbox.abnamro.com/v1/ais/transactions?accountNumber=".$accountNumber."&bookDateFrom='.$dateFrom.'&bookDateTo='.$dateTo.'&nextPageKey=".$nextPage;
            }

            $json = $this->curl('GET', ['url'=>$url, 'options'=> "Authorization: Bearer ".$token['access_token']]);

            die($json);

            $json = explode('{', $json);
            $json[0] = "";
            $json = implode('{', $json);

            $json = json_decode($json, true);

            array_push($transactions, $json);

            if(empty($json['transactionsList']['nextPageKey'])) $next = false;
            else $nextPage = $json['transactionsList']['nextPageKey'];
        }

        return $transactions;
    }

    /**
     * Get the Balance on the ABN account
     *
     * @param array $token
     * @param null $accountNumber
     * @return mixed
     */
    public function getBalance(array $token, $accountNumber = null)
    {
        if(!$accountNumber)$accountNumber = env('ABN_ACCOUNT_NUMBER');

        $json = $this->curl('GET',['url'=>"https://api-sandbox.abnamro.com/v1/ais/accountbalances?accountNumber=".$accountNumber,'options' =>["Authorization: Bearer ".$token['access_token']]]);

        $json = explode('{', $json);
        $json[0] = "";
        $json = implode('{', $json);

        return json_decode($json, true);
    }

    /**
     * Gets Account details
     *
     * @param array $token
     * @param null $accountNumber
     * @return mixed
     */

    public function getAccountInfo(array $token, $accountNumber = null)
    {
        if(!$accountNumber)$accountNumber = env('ABN_ACCOUNT_NUMBER');

        $json = $this->curl('GET',['url' => "https://api-sandbox.abnamro.com/v1/ais/accounts/".$accountNumber,'options' =>["Authorization: Bearer ".$token['access_token']]] );

        $json = explode('{', $json);
        $json[0] = "";
        $json = implode('{', $json);

        return json_decode($json, true);
    }

    /**
     * Build the CRUL and does the crul requests
     *
     * @param $type
     * @param array $parameter
     * @return mixed
     */

    private function curl($type, array $parameter)
    {
        $url     = $parameter['url'];
        $options = $parameter['options'];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_FRESH_CONNECT,1);

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $options);
        if($type == "GET")
        {
            curl_setopt($curl, CURLOPT_POST, 0);
        }
        else
        {
            $data = $parameter['data'];

            curl_setopt($curl, CURLOPT_POSTFIELDS,$data);
            curl_setopt($curl, CURLOPT_POST, 1);
        }

        curl_setopt($curl, CURLOPT_HEADER,1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $json = curl_exec($curl);

        curl_close($curl);
        return $json;
    }

}