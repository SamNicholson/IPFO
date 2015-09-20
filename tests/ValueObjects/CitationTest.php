<?php

namespace src\Tests\ValueObjects;

use SNicholson\IPFO\ValueObjects\Citation;

class CitationTest extends \PHPUnit_Framework_TestCase
{
    public function testPatentCitationSetsPropertiesCorrectly()
    {
        $citation = Citation::patent('number', 'country', 'citedBy');
        $this->assertEquals(Citation::PATENT, $citation->getType());
        $this->assertEquals('number', $citation->getNumber());
        $this->assertEquals('country', $citation->getCountry());
        $this->assertEquals('citedBy', $citation->getCitedBy());
    }

    public function testNonPatentLiteratureSetsPropertiesCorrectly()
    {
        $citation = Citation::nonPatentLiterature('text', 'citedBy');
        $this->assertEquals(Citation::NON_PATENT_LITERATURE, $citation->getType());
        $this->assertEquals('text', $citation->getText());
        $this->assertEquals('citedBy', $citation->getCitedBy());
    }
}
