<?php

namespace Tests\Unit;

use Mockery as m;
use resources\services\ABN\AbnService;
use Tests\TestCase;

class TransactionTest extends TestCase
{

    public function testTransactions()
    {
        $abnService = m::mock(AbnService::class);

        $requestData = 'HTTP/1.1 200 OKDate: Sat, 10 Jun 2017 19:14:30 GMTContent-Type: application/json; charset=utf-8Content-Length: 5594Connection: keep-aliveX-Powered-By: ExpressServer: Apigee Router{"transactionsList":{"transactions":[{"transaction":{"accountNumber":"NL82ABNA0236536203","accountServicerReference":"TRANREF00509","amount":-199,"balanceAfterMutation":11210.34,"bookDate":"2017-06-10","counterPartyAccountNumber":"NL37RABO0383426324","counterPartyName":"Blomsma Print & Sign Projecten BV","currency":"EUR","descriptionLines":["SEPA Overboeking","IBAN: NL37RABO0383426324","BIC: ABNANL2A","Naam: W GKSOX EWH FRRXLF FJY","Omschrijving: 6859/178958"],"status":"INPROGRESS","transactionId":"TRANREF00509","transactionTimestamp":"2017-06-10-00:00:00.000","valueDate":"2017-06-10"}}]}}';

        $abnService->shouldReceive('curl')->andReturn($requestData)->once();

        $result = $abnService->getTransactions('','','','');

        $this->assertSame(json_encode('{"transactionsList":{"transactions":[{"transaction":{"accountNumber":"NL82ABNA0236536203","accountServicerReference":"TRANREF00509","amount":-199,"balanceAfterMutation":11210.34,"bookDate":"2017-06-10","counterPartyAccountNumber":"NL37RABO0383426324","counterPartyName":"Blomsma Print & Sign Projecten BV","currency":"EUR","descriptionLines":["SEPA Overboeking","IBAN: NL37RABO0383426324","BIC: ABNANL2A","Naam: W GKSOX EWH FRRXLF FJY","Omschrijving: 6859/178958"],"status":"INPROGRESS","transactionId":"TRANREF00509","transactionTimestamp":"2017-06-10-00:00:00.000","valueDate":"2017-06-10"}}]}}'), json_encode($result));
    }

    public function tearDown()
    {
        m::close();
    }
}

