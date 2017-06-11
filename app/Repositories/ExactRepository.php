<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Picqer\Financials\Exact\PurchaseEntry;
use Picqer\Financials\Exact\SalesEntry;
use Picqer\Financials\Exact\SalesEntryLine;

class ExactRepository extends BaseRepository
{
    public static $Sales;
    public static $Purchase;

    public Static function Sales() {
        return ExactRepository::$Sales;
    }

    function getValue($key)
    {
        $storage = json_decode(file_get_contents('../app/Repositories/storage.json'), true);
        if (array_key_exists($key, $storage)) {
            return $storage[$key];
        }
        return null;
    }

    function setValue($key, $value)
    {
        $storage       = json_decode(file_get_contents('../app/Repositories/storage.json'), true);
        $storage[$key] = $value;
        file_put_contents('../app/Repositories/storage.json', json_encode($storage));
    }

    function authorize()
    {
        $connection = new \Picqer\Financials\Exact\Connection();
        $connection->setRedirectUrl('http://banklinq.azurewebsites.net/ExactBack'); // Same as entered online in the App Center
        $connection->setExactClientId('9577d765-5430-45e5-9d48-a19217462344');
        $connection->setExactClientSecret('LtV8RW4nDd2Q');
        $connection->redirectForAuthorization();
    }

    function tokenUpdateCallback(\Picqer\Financials\Exact\Connection $connection) {
        // Save the new tokens for next connections
        $this->setValue('accesstoken', $connection->getAccessToken());
        $this->setValue('refreshtoken', $connection->getRefreshToken());
        // Save expires time for next connections
        $this->setValue('expires_in', $connection->getTokenExpires());
    }

    function dochecks($connection) {
        if ($this->getValue('authorizationcode')) {
            $connection->setAuthorizationCode($this->getValue('authorizationcode'));
        }
        if ($this->getValue('accesstoken')) {
            $connection->setAccessToken($this->getValue('accesstoken'));
        }
        if ($this->getValue('refreshtoken')) {
            $connection->setRefreshToken($this->getValue('refreshtoken'));
        }
        if ($this->getValue('expires_in')) {
            $connection->setTokenExpires($this->getValue('expires_in'));
        }

        return $connection;
    }

    function connect()
    {
        $connection = new \Picqer\Financials\Exact\Connection();
        $connection->setRedirectUrl('http://banklinq.azurewebsites.net/ExactBack');
        $connection->setExactClientId('9577d765-5430-45e5-9d48-a19217462344');
        $connection->setExactClientSecret('LtV8RW4nDd2Q');

        $connection = $this->dochecks($connection);

        $connection->setTokenUpdateCallback('tokenUpdateCallback');

        try {
            $connection->connect();
        } catch (\Exception $e) {
            throw new Exception('Could not connect to Exact: ' . $e->getMessage());
        }

        // Save the new tokens for next connections
        $this->setValue('accesstoken', serialize($connection->getAccessToken()));
        $this->setValue('refreshtoken', $connection->getRefreshToken());

        // Optionally, save the expiry-timestamp. This prevents exchanging valid tokens (ie. saves you some requests)
        $this->setValue('expires_in', $connection->getTokenExpires());

        return $connection;
    }

    function StartConnection()
    {
        if (isset($_GET['code']) && is_null($this->getValue('authorizationcode'))) {
            $this->setValue('authorizationcode', $_GET['code']);
        }
        // If we do not have a authorization code, authorize first to setup tokens
        if ($this->getValue('authorizationcode') === null) {
            $this->authorize();
        }
        // Create the Exact client
        $connection = $this->connect();

        return $connection;
    }

    /**
     * @param $Amount - Double (Cash)
     */
    public function CreateSalesEntry($Amount) {

        $connection = $this->StartConnection();

        //$this->GetRandom($connection);

        $SalesEntryLine = array(
            'AmountFC'  => $Amount,
            'EntryID'   => "3e6687ff-7bb1-40b6-967b-97af1a6dc5cc",
            'GLAccount' => "efd95952-892b-4a00-a4b1-3888b785d611",
        );

        $SalesEntry = new SalesEntry($connection);
        $SalesEntry->Customer = "ff0d02ef-1d14-4fba-a855-cb2e2b725953";
        $SalesEntry->Journal = "70";
        $SalesEntry->addItem($SalesEntryLine);

        $SalesEntry->save();

    }

    /**
     * @param $Amount - Double (Cash)
     */
    public function CreatePurchaseEntry($Amount) {

        $connection = $this->StartConnection();

        //$this->GetRandom($connection);

        $PurchaseEntryLine = array(
            'AmountFC'=> $Amount,
            //'EntryID'=> "3e6687ff-7bb1-40b6-967b-97af1a6dc5cc",
            'EntryID'=> "c5657cd3-af60-40d6-9312-5ccc49286f67",
            'GLAccount'=>"efd95952-892b-4a00-a4b1-3888b785d611",
        );

        $PurchaseEntry = new PurchaseEntry($connection);
        $PurchaseEntry->Supplier = "ff0d02ef-1d14-4fba-a855-cb2e2b725953";
        $PurchaseEntry->Journal = "60";
        $PurchaseEntry->addItem($PurchaseEntryLine);

        $PurchaseEntry->save();
    }

    public function GetRandom($connection){
        try {
            $journals = new \Picqer\Financials\Exact\PurchaseEntry($connection);
            $result   = $journals->get();
            foreach ($result as $journal) {

            }
        } catch (\Exception $e) {
            echo get_class($e) . ' : ' . $e->getMessage();
        }
    }
}
