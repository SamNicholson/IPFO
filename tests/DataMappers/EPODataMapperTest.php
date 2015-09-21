<?php

use SNicholson\IPFO\EPO\EPODataMapper;
use SNicholson\IPFO\Result;
use SNicholson\IPFO\Searches\SearchError;
use SNicholson\IPFO\ValueObjects\Priority;
use SNicholson\IPFO\ValueObjects\SearchSource;

class EPODataMapperTest extends PHPUnit_Framework_TestCase
{
    public function testNotFoundReturnsNotFound()
    {
        $epoMapper = new EPODataMapper();
        $epoMapper->setResponse(json_decode(file_get_contents(__DIR__ . '/sample/EPO/notFound.sample'), true));
        $this->assertEquals(
            SearchError::fromString('Unable to locate Patent in the EPO Database'),
            $epoMapper->getSearchResult()
        );
    }

    public function testNullStringReturnsNullError()
    {
        $epoMapper = new EPODataMapper();
        $epoMapper->setResponse(null);
        $this->assertEquals(
            SearchError::fromString('Attempted to map data which is null'),
            $epoMapper->getSearchResult()
        );
    }

    public function testEPODataMapperSetsSourceCorrectly()
    {
        $actualResult = $this->getPublicationEPODataMapperResult();
        $expectedResult = $this->getExpectedPublicationResult();
        $this->assertEquals($expectedResult->getSource(), $actualResult->getSource());
    }

    public function testTitlesParsedCorrectly()
    {
        $actualResult = $this->getPublicationEPODataMapperResult();
        $expectedResult = $this->getExpectedPublicationResult();
        $this->assertEquals($expectedResult->getEnglishTitle(), $actualResult->getEnglishTitle());
        $this->assertEquals($expectedResult->getFrenchTitle(), $actualResult->getFrenchTitle());
        $this->assertEquals($expectedResult->getGermanTitle(), $actualResult->getGermanTitle());
    }

    public function testApplicationDetailsParsedCorrectly()
    {
        $actualResult = $this->getPublicationEPODataMapperResult();
        $expectedResult = $this->getExpectedPublicationResult();
        $this->assertEquals($expectedResult->getApplicationCountry(), $actualResult->getApplicationCountry());
        $this->assertEquals($expectedResult->getApplicationNumber(), $actualResult->getApplicationNumber());
        $this->assertEquals($expectedResult->getApplicationDate(), $actualResult->getApplicationDate());
    }

    public function testPublicationDetailsParsedCorrectly()
    {
        $actualResult = $this->getPublicationEPODataMapperResult();
        $expectedResult = $this->getExpectedPublicationResult();
        $this->assertEquals($expectedResult->getPublicationCountry(), $actualResult->getPublicationCountry());
        $this->assertEquals($expectedResult->getPublicationNumber(), $actualResult->getPublicationNumber());
        $this->assertEquals($expectedResult->getPublicationDate(), $actualResult->getPublicationDate());
    }

    public function testGrantDetailsParsedCorrectly()
    {

    }

    public function testPrioritiesAreParsedCorrectly()
    {
        $actualResult = $this->getPublicationEPODataMapperResult();
        $expectedResult = $this->getExpectedPublicationResult();
        $this->assertEquals($expectedResult->getPriorities(), $actualResult->getPriorities());
    }

    public function testApplicantsAreParsedCorrectly()
    {
        $actualResult = $this->getPublicationEPODataMapperResult();
        $expectedResult = $this->getExpectedPublicationResult();
        $this->assertEquals($expectedResult->getApplicants(), $actualResult->getApplicants());
    }

    public function testInventorsAreParsedCorrectly()
    {
        $actualResult = $this->getPublicationEPODataMapperResult();
        $expectedResult = $this->getExpectedPublicationResult();
        $this->assertEquals($expectedResult->getInventors(), $actualResult->getInventors());
    }

    private function getPublicationEPODataMapperResult()
    {
        $epoMapper = new EPODataMapper();
        $stringResponse = json_decode(file_get_contents(__DIR__ . '/sample/EPO/publication.sample'), true);
        $epoMapper->setResponse($stringResponse);
        return $epoMapper->getSearchResult();
    }

    private function getExpectedPublicationResult()
    {
        //Setup the expected publication result
        $expectedResult = new Result();
        $expectedResult->setSource(SearchSource::EPO());

        //titles
        $expectedResult->setEnglishTitle(
            'NICKING AND EXTENSION AMPLIFICATION REACTION FOR THE EXPONENTIAL AMPLIFICATION OF NUCLEIC ACIDS'
        );
        $expectedResult->setFrenchTitle(
            'RÃ‰ACTION D\'AMPLIFICATION DE SYNCHRONISATION ET D\'EXTENSION POUR L\'AMPLIFICATION ' .
            'EXPONENTIELLE D\'ACIDES NUCLÃ‰IQUES'
        );
        $expectedResult->setGermanTitle(
            'NICKING- UND VERLÃ„NGERUNGS-AMPLIFIKATIONSREAKTION ZUR EXPONENTIELLEN AMPLIFIKATION VON NUKLEINSÃ„UREN'
        );

        //application information
        $expectedResult->setApplicationCountry('EP');
        $expectedResult->setApplicationDate('20080714');
        $expectedResult->setApplicationNumber('08781827');

        //publication information
        $expectedResult->setPublicationCountry('EP');
        $expectedResult->setPublicationDate('20100505');
        $expectedResult->setPublicationNumber('2181196');

        //Priorities
        $firstPriority = Priority::fromNumber('WO2008US70023');
        $firstPriority->setDate('20080714');
        $secondPriority = Priority::fromNumber('US20070778018');
        $secondPriority->setDate('20070714');
        $expectedResult->setPriorities($firstPriority, $secondPriority);

        //Applicants
        $applicantParty = new \SNicholson\IPFO\ValueObjects\Party();
        $applicant1 = new \SNicholson\IPFO\ValueObjects\Applicant();
        $applicant1->setName('IONIAN TECHNOLOGIES, INC');
        $applicant1->setSequence('1');
        $applicantParty->addMember($applicant1);
        $expectedResult->setApplicants($applicantParty);

        //Inventors
        $inventorParty = new \SNicholson\IPFO\ValueObjects\Party();
        $inventor1 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor1->setName('MAPLES, BRIAN, K,');
        $inventor1->setSequence('1');
        $inventorParty->addMember($inventor1);
        $inventor2 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor2->setName('HOLMBERG, REBECCA, C,');
        $inventor2->setSequence('2');
        $inventorParty->addMember($inventor2);
        $inventor3 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor3->setName('MILLER, ANDREW, P,');
        $inventor3->setSequence('3');
        $inventorParty->addMember($inventor3);
        $inventor4 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor4->setName('PROVINS, JARROD,');
        $inventor4->setSequence('4');
        $inventorParty->addMember($inventor4);
        $inventor5 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor5->setName('ROTH, RICHARD,');
        $inventor5->setSequence('5');
        $inventorParty->addMember($inventor5);
        $inventor6 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor6->setName('MANDELL, JEFFREY');
        $inventor6->setSequence('6');
        $inventorParty->addMember($inventor6);
        $expectedResult->setInventors($inventorParty);

        return $expectedResult;
    }
}
