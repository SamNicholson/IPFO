<?php

class USPTODataMapperTest extends PHPUnit_Framework_TestCase
{

    public function testApplicationInformationIsPulledProperly()
    {
        $result         = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getApplicationDate(), $result->getApplicationDate());
        $this->assertEquals($expectedResult->getApplicationNumber(), $result->getApplicationNumber());
        $this->assertEquals($expectedResult->getApplicationCountry(), $result->getApplicationCountry());
    }

    public function testGrantInformationIsPulledProperly()
    {
        $result         = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getGrantDate(), $result->getGrantDate());
        $this->assertEquals($expectedResult->getGrantNumber(), $result->getGrantNumber());
        $this->assertEquals($expectedResult->getGrantCountry(), $result->getGrantCountry());
    }

    public function testInventorsArePulledCorrectly()
    {
        $result         = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getInventors(), $result->getInventors());
    }
    public function testApplicantsArePulledCorrectly()
    {
        $result         = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getApplicants(), $result->getApplicants());
    }

    public function testTitleIsParsedCorrectly()
    {
        $result         = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getEnglishTitle(), $result->getEnglishTitle());
    }

    private function getPublicationSample()
    {
        $USPTO = new \SNicholson\IPFO\USPTO\USPTODataMapper();
        $USPTO->setResponse(file_get_contents(__DIR__ . '/sample/USPTO/publication.sample'));
        return $USPTO->getSearchResult();
    }

    private function getExpectedPublication()
    {
        $result = new \SNicholson\IPFO\Result();
        //Application
        $result->setApplicationDate('2003-09-02');
        $result->setApplicationNumber('10/653,633');
        $result->setApplicationCountry('US');
        //Grant
        $result->setGrantNumber('6,923,014');
        $result->setGrantDate('2005-08-02');
        $result->setGrantCountry('US');
        //Titles
        $result->setEnglishTitle('System for cooling multiple logic molecules');
        //Inventors
        $inventors = new \SNicholson\IPFO\ValueObjects\Party();
        $inventor1 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor1->setName('Goth; Gary F.');
        $inventor1->setSequence(1);
        $inventors->addMember($inventor1);
        $inventor2 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor2->setName('Kearney; Daniel J.');
        $inventor2->setSequence(2);
        $inventors->addMember($inventor2);
        $inventor3 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor3->setName('Makowicki; Robert P.');
        $inventor3->setSequence(3);
        $inventors->addMember($inventor3);
        $inventor4 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor4->setName('McClafferty; W. David');
        $inventor4->setSequence(4);
        $inventors->addMember($inventor4);
        $inventor5 = new \SNicholson\IPFO\ValueObjects\Inventor();
        $inventor5->setName('Porter; Donald W.');
        $inventor5->setSequence(5);
        $inventors->addMember($inventor5);
        $result->setInventors($inventors);
        //Applicants
        $applicant = new \SNicholson\IPFO\ValueObjects\Applicant();
        $applicant->setName('International Business Machines Corporation');
        $applicant->setSequence(1);
        $applicants = new \SNicholson\IPFO\ValueObjects\Party();
        $applicants->addMember($applicant);
        $result->setApplicants($applicants);
        return $result;
    }
}
