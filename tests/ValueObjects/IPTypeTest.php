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
        $this->assertEquals(IPType::PATENT, IPType::patent()->__toString());
    }

    /**
     * @test Test Trademark returns String Correctly
     */
    public function testTradeMarkReturnsStringCorrectly()
    {
        $this->assertEquals(IPType::TRADEMARK, IPType::tradeMark()->__toString());
    }
}
