<?php

namespace src\Tests;

use SNicholson\IPFO\Result;
use SNicholson\IPFO\Search;
use SNicholson\IPFO\ValueObjects\IPType;
use SNicholson\IPFO\ValueObjects\Number;

class SearchTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $searchInterfaceMock;

    public function setUp()
    {
        $this->searchInterfaceMock = $this->getMockBuilder('SNicholson\IPFO\Searches\PatentSearch')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test Test Search By Trade Mark Application Sets Correct Objects
     */
    public function testSearchByTradeMarkApplicationSetsCorrectObjects()
    {
        $search = Search::tradeMark()->byApplicationNumber('EP12345');
        $this->assertEquals(IPType::tradeMark(), $search->getIPType());
        $this->assertEquals(Number::application('EP12345'), $search->getNumber());
    }

    /**
     * @test Test Search By Patent Application Sets Correct Objects
     */
    public function testSearchByPatentApplicationSetsCorrectObjects()
    {
        $search = Search::patent()->byApplicationNumber('EP12345');
        $this->assertEquals(IPType::patent(), $search->getIPType());
        $this->assertEquals(Number::application('EP12345'), $search->getNumber());
    }

    /**
     * @test Test Search By Trade Mark Publication Sets Correct Objects
     */
    public function testSearchByTradeMarkPublicationSetsCorrectObjects()
    {
        $search = Search::tradeMark()->byPublicationNumber('EP12345');
        $this->assertEquals(IPType::tradeMark(), $search->getIPType());
        $this->assertEquals(Number::publication('EP12345'), $search->getNumber());
    }

    /**
     * @test Test Search By Patent Publication Sets Correct Objects
     */
    public function testSearchByPatentPublicationSetsCorrectObjects()
    {
        $search = Search::patent()->byPublicationNumber('EP12345');
        $this->assertEquals(IPType::patent(), $search->getIPType());
        $this->assertEquals(Number::publication('EP12345'), $search->getNumber());
    }

    /**
     * @test Test Search calls Search InterFace Correctly
     */
    public function testSearchCallsSearchInterFaceCorrectly()
    {
        $search = Search::patent()->byPublicationNumber('EP12345');
        $this->searchInterfaceMock->expects($this->once())->method('numberSearch')->with($search->getNumber())
            ->willReturn(new Result());
        $search->run($this->searchInterfaceMock);
    }
}
