<?php

namespace src\Tests\ValueObjects;

use WorkAnyWare\IPFO\IPRights\SearchType;

class IPTypeTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test Test Patent Returns String Correctly
     */
    public function testPatentReturnsStringCorrectly()
    {
        $this->assertEquals(SearchType::PATENT, SearchType::patent()->__toString());
    }

    /**
     * @test Test Trademark returns String Correctly
     */
    public function testTradeMarkReturnsStringCorrectly()
    {
        $this->assertEquals(SearchType::TRADEMARK, SearchType::tradeMark()->__toString());
    }
}
