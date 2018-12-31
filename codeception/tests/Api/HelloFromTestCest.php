<?php

namespace Tests\Api;

use \ApiTester;
use App\Tests\Api\BaseApiCest;

class HelloFromTestCest extends BaseApiCest
{
    public function run(ApiTester $I)
    {
        $I->wantTo('Run');
    }
}
