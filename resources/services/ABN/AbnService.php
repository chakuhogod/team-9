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

        $json = $this->curl('POST', ['url'=>"https://api-sandbox.abnamro.com/v1/oauth/token", 'options'=>$options, 'data'=>'grant_type=client_credentials&scope=ais&accountNumber='. env('ABN_ACCOUNT_NUMBER')] );

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
     * @param  array $token
     * @param  array $data
     * @return mixed
     */

    public function getTransactions(array $token, array $data )
    {
        $accountNumber = $data['accountNumber'];
        $dateFrom      = $data['dateFrom'];
        $dateTo        = $data['dateTo'];

        if(!$accountNumber)$accountNumber = env('ABN_ACCOUNT_NUMBER');
        $next = true;
        $nextPage = Null;

        $transactions = [];

        while ($next)
        {

            if(empty($nextPage))
            {
                $url = "https://api-sandbox.abnamro.com/v1/ais/transactions?accountNumber=".$accountNumber."&bookDateFrom=2016-05-10&bookDateTo=2017-06-10";
            }
            else
            {
                $url = "https://api-sandbox.abnamro.com/v1/ais/transactions?accountNumber=".$accountNumber."&bookDateFrom=2016-05-10&bookDateTo=2017-06-10&nextPageKey=".$nextPage;
            }
            $options = ["Authorization: Bearer ".$token['access_token']];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FRESH_CONNECT,1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options);
            curl_setopt($curl, CURLOPT_POST, 0);
            curl_setopt($curl, CURLOPT_HEADER,1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            $json = curl_exec($curl);
            curl_close($curl);
            $json = json_decode($json, true);
            array_push($transactions, $json);
            if(empty($json['transactionsList']['nextPageKey'])) $next = false;
            else $nextPage = $json['transactionsList']['nextPageKey'];
        }
        $endTime = microtime(true);
        $timeTaken = $starttime - $endTime;
        return $transactions;
    }

    /**
     * Returns key-value based transactions
     *
     * @return mixed
     */
    public function getTransactionsWithKeyAmount(array $token, $accountNumber = null){
        if(!$accountNumber)$accountNumber = env('ABN_ACCOUNT_NUMBER');
        $next = true;
        $nextPage = null;

        $return = array();

        while ($next)
        {
            if(empty($nextPage))
            {
                $url = "https://api-sandbox.abnamro.com/v1/ais/transactions?accountNumber=".$accountNumber."&bookDateFrom=2016-05-10&bookDateTo=2017-06-10";
            }
            else
            {
                $url = "https://api-sandbox.abnamro.com/v1/ais/transactions?accountNumber=".$accountNumber."&bookDateFrom=2016-05-10&bookDateTo=2017-06-10&nextPageKey=".$nextPage;
            }
            $options = ["Authorization: Bearer ".$token['access_token']];
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_FRESH_CONNECT,1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options);
            curl_setopt($curl, CURLOPT_POST, 0);
            curl_setopt($curl, CURLOPT_HEADER,1);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            $json = curl_exec($curl);
            curl_close($curl);
            $temp = json_decode($json, true);


            foreach($temp as $transactionList){
                foreach($transactionList as $transactions){
                    //die(var_dump($transactions));
                    if(is_array($transactions)){
                        foreach($transactions as $transaction){
                            foreach($transaction as $innerTransaction){
                                $encodedTransaction = json_encode($innerTransaction);
                                $finalTransaction = json_decode($encodedTransaction, true);

                                $date = $finalTransaction["accountServicerReference"];
                                $amount = $finalTransaction["balanceAfterMutation"];
                                //array_push($return, $amount);
                                array_push($return, $finalTransaction["balanceAfterMutation"]);
                                //die(json_encode($return));
                            }
                        }
                    }
                }
            }

            if(empty($json['transactionsList']['nextPageKey'])){
                $next = false;
            } else {
                $nextPage = $json['transactionsList']['nextPageKey'];
            }
        }

        return array("data" => $return);
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

        $url = "https://api-sandbox.abnamro.com/v1/ais/accountbalances?accountNumber=".$accountNumber;

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

        $url = "https://api-sandbox.abnamro.com/v1/ais/accounts/".$accountNumber;

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


        $json = explode('{', $json);
        $json[0] = "";
        $json = implode('{', $json);

        return json_decode($json, true);
    }

}