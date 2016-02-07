<?php

namespace src\Tests\ValueObjects;

use SNicholson\IPFO\Parties\Inventor;

class InventorTest extends \PHPUnit_Framework_TestCase
{

    public function testTrailingCommaIsPulledFromInventor()
    {
        $inventor  = new Inventor();
        $inventor->setName('Test,');
        $this->assertEquals('Test', $inventor->getName());
    }
}
