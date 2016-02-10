<?php

class PatentSearchTest extends PHPUnit_Framework_TestCase
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
        $this->requestMock = $this->getMockBuilder('SNicholson\IPFO\Requests\USPTORequest')
            ->disableOriginalConstructor()
            ->setMethods(['simpleNumberSearch', 'getError'])
            ->getMock();
    }

    /**
     * @test Test EP Request Fired From EP Number Successfully
     */
    public function testEPRequestFired()
    {
        $number = \SNicholson\IPFO\Helpers\RightNumber::application('EP12345');
        $this->requestsContainerMock->expects($this->once())->method('newEPORequest')->willReturn($this->requestMock);
        $this->requestMock->expects($this->once())->method('simpleNumberSearch')->with($number)->willReturn(true);
        $this->assertTrue($this->getPatentSearch()->numberSearch($number));
    }

    /**
     * @test Test USPTO Request Fired from USPTO Number
     */
    public function testUSPTORequestFired()
    {
        $number = \SNicholson\IPFO\Helpers\RightNumber::application('US12345');
        $this->requestsContainerMock->expects($this->once())->method('newUSPTORequest')->willReturn($this->requestMock);
        $this->requestMock->expects($this->once())->method('simpleNumberSearch')->with($number)->willReturn(true);
        $this->assertTrue($this->getPatentSearch()->numberSearch($number));
    }

    /**
     * @test Test WIPO Request Fired From Non USPTO or EP Number
     */
    public function testWIPORequestFired()
    {
        $number = \SNicholson\IPFO\Helpers\RightNumber::application('WO12345');
        $this->requestsContainerMock->expects($this->once())->method('newWIPORequest')->willReturn($this->requestMock);
        $this->requestMock->expects($this->once())->method('simpleNumberSearch')->with($number)->willReturn(true);
        $this->assertTrue($this->getPatentSearch()->numberSearch($number));
    }

    /**
     * @test Test Failed Number Search Will Trigger Failure and Error
     */
    public function testFailedNumberSearchWillTriggerFailureAndError()
    {
        $number = \SNicholson\IPFO\Helpers\RightNumber::application('WO12345');
        $patentSearch = $this->getPatentSearch();
        $this->requestsContainerMock->expects($this->once())->method('newWIPORequest')->willReturn($this->requestMock);
        $this->requestMock->expects($this->once())->method('simpleNumberSearch')->with($number)->willReturn(false);
        $this->requestMock->expects($this->once())->method('getError')->willReturn('test');
        $this->assertFalse($patentSearch->numberSearch($number));
        $this->assertEquals('test', $patentSearch->getError());
    }

    private function getPatentSearch()
    {
        return new \SNicholson\IPFO\Searches\PatentSearch($this->requestsContainerMock);
    }
}
