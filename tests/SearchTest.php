<?php

namespace src\Tests;

use SNicholson\IPFO\Search;

class SearchTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test Test Search By Trade Mark Application Sets Correct Objects
     */
    public function testSearchByTradeMarkApplicationSetsCorrectObjects()
    {
        Search::tradeMark()->byApplicationNumber('EP12345GB');
    }
}
