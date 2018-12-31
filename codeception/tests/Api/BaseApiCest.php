<?php

namespace App\Tests\Api;

use \ApiTester;

class BaseApiCest
{
    public function _before(ApiTester $I)
    {
        $this->setRequestHeaders($I);
    }

    protected function setRequestHeaders(ApiTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');
    }
}
