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

        $curl = m::mock(curl::class);

        $abnService->shouldReceive('curl_init')->andReturn();
    }

    public function tearDown()
    {
        m::close();
    }
}

