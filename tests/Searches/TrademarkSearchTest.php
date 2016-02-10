<?php

class TrademarkSearchTest extends PHPUnit_Framework_TestCase
{


    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestsContainerMock;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    public function setUp()
    {
        $this->requestsContainerMock = $this->getMockBuilder('SNicholson\IPFO\Containers\RequestsContainer')
                                            ->disableOriginalConstructor()
                                            ->getMock();
        $this->requestMock = $this->getMockBuilder('SNicholson\IPFO\Requests\USPTOTrademarkRequest')
                                  ->disableOriginalConstructor()
                                  ->getMock();
    }

    /**
     * @test Test USPTO Request Fired From EP Number Successfully
     */
    public function testUSPTOTradeMarkSearch()
    {
        $number = \SNicholson\IPFO\Helpers\RightNumber::application('US12345');
        $this->requestsContainerMock->expects($this->once())->method('newUSPTOTrademarkRequest')
            ->willReturn($this->requestMock);
        $this->requestMock->expects($this->once())->method('simpleNumberSearch')->with($number)->willReturn(true);
        $this->assertTrue($this->getTradeMarkSearch()->numberSearch($number));
    }

    /**
     * @test Test Failed Number Search Will Trigger Failure and Error
     */
    public function testFailedNumberSearchWillTriggerFailureAndError()
    {
        $number = \SNicholson\IPFO\Helpers\RightNumber::application('WO12345');
        $tradeMarkSearch = $this->getTradeMarkSearch();
        $this->requestsContainerMock->expects($this->once())->method('newUSPTOTrademarkRequest')->willReturn($this->requestMock);
        $this->requestMock->expects($this->once())->method('simpleNumberSearch')->with($number)->willReturn(false);
        $this->requestMock->expects($this->once())->method('getError')->willReturn('test');
        $this->assertFalse($tradeMarkSearch->numberSearch($number));
        $this->assertEquals('test', $tradeMarkSearch->getError());
    }

    private function getTradeMarkSearch()
    {
        return new \SNicholson\IPFO\Searches\TrademarkSearch($this->requestsContainerMock);
    }
}
