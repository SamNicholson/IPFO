<?php

namespace tests\ValueObjects;


use SNicholson\IPFO\ValueObjects\Number;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test Test Application Number Returns Appropriate Type
     */
    public function testApplicationNumberReturnsAppropriateType()
    {
        $number = Number::application('12345GB');
        $this->assertEquals('application', $number->getType());
        $this->assertEquals('12345GB', $number->getNumberString());
    }
    /**
     * @test Test Publication Number Returns Appropriate Type
     */
    public function testPublicationNumberReturnsAppropriateType()
    {
        $number = Number::publication('12345GB');
        $this->assertEquals('publication', $number->getType());
        $this->assertEquals('12345GB', $number->getNumberString());
    }
}
