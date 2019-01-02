<?php

namespace App\Tests\Functional;

use \FunctionalTester;

class BaseFunctionalCest
{
    public function _before(FunctionalTester $I)
    {
        $this->setRequestHeaders($I);
    }

    protected function setRequestHeaders(FunctionalTester $I)
    {
        $I->haveHttpHeader('Accept', 'application/json');
        $I->haveHttpHeader('Content-Type', 'application/json');
    }
}
