<?php

namespace tests\ValueObjects;

use SNicholson\IPFO\ValueObjects\SearchSource;

class SearchSourceTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test Test USPTO
     */
    public function  testUSPTO()
    {
        $this->assertEquals('USPTO', SearchSource::USPTO()->__toString());
    }

    /**
     * @test Test EPO
     */
    public function testEPO()
    {
        $this->assertEquals('EPO', SearchSource::EPO()->__toString());
    }

    /**
     * @test Test WIPO
     */
    public function testWIPO()
    {
        $this->assertEquals('WIPO', SearchSource::WIPO()->__toString());
    }
}
