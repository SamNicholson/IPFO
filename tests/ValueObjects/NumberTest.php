<?php

namespace tests\ValueObjects;

use SNicholson\IPFO\Helpers\RightNumber;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test Test Application Number Returns Appropriate Type
     */
    public function testApplicationNumberReturnsAppropriateType()
    {
        $number = RightNumber::application('12345GB');
        $this->assertEquals('application', $number->getType());
        $this->assertEquals('12345GB', $number->getNumberString());
        $this->assertEquals('12345GB', $number->__toString());
    }

    /**
     * @test Test Publication Number Returns Appropriate Type
     */
    public function testPublicationNumberReturnsAppropriateType()
    {
        $number = RightNumber::publication('12345GB');
        $this->assertEquals('publication', $number->getType());
        $this->assertEquals('12345GB', $number->__toString());
        $this->assertEquals('12345GB', $number->__toString());
    }
}
