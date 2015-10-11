<?php

namespace src\Tests\ValueObjects;

use SNicholson\IPFO\ValueObjects\Priority;

class PriorityTest extends \PHPUnit_Framework_TestCase
{

    public function testCountryIsPulledFromNumberIfNoCountrySet()
    {
        $priority = Priority::fromNumber('WO123');
        $this->assertEquals('WO', $priority->getCountry());
        $priority = Priority::fromNumber('123');
        $this->assertEquals(null, $priority->getCountry());
    }
}
