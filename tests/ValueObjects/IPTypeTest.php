<?php

namespace src\Tests\ValueObjects;

use SNicholson\IPFO\ValueObjects\IPType;

class IPTypeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test Test Patent Returns String Correctly
     */
    public function testPatentReturnsStringCorrectly()
    {
        $this->assertEquals('Patent', IPType::patent()->__toString());
    }

    /**
     * @test Test Trademark returns String Correctly
     */
    public function testTradeMarkReturnsStringCorrectly()
    {
        $this->assertEquals('TradeMark', IPType::tradeMark()->__toString());
    }
}
