<?php

use SNicholson\IPFO\IPRight;
use SNicholson\IPFO\IPRightInterface;
use SNicholson\IPFO\ValueObjects\Citation;

class USPTODataMapperTest extends PHPUnit_Framework_TestCase
{

    public function testApplicationInformationIsPulledProperly()
    {
        $result = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getApplicationDate(), $result->getApplicationDate());
        $this->assertEquals($expectedResult->getApplicationNumber(), $result->getApplicationNumber());
        $this->assertEquals($expectedResult->getApplicationCountry(), $result->getApplicationCountry());
    }

    public function testGrantInformationIsPulledProperly()
    {
        $result = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getGrantDate(), $result->getGrantDate());
        $this->assertEquals($expectedResult->getGrantNumber(), $result->getGrantNumber());
        $this->assertEquals($expectedResult->getGrantCountry(), $result->getGrantCountry());
    }

    public function testInventorsArePulledCorrectly()
    {
        $result = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getInventors(), $result->getInventors());
    }

    public function testApplicantsArePulledCorrectly()
    {
        $result = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getApplicants(), $result->getApplicants());
    }

    public function testTitleIsParsedCorrectly()
    {
        $result = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getEnglishTitle(), $result->getEnglishTitle());
    }

    public function testCitationsAreParsedCorrectly()
    {
        $result = $this->getPublicationSample();
        $expectedResult = $this->getExpectedPublication();
        $this->assertEquals($expectedResult->getCitations(), $result->getCitations());
    }

    private function getPublicationSample()
    {
        $USPTO = new \SNicholson\IPFO\Searches\USPTO\USPTODataMapper();
        $USPTO->setResponse(file_get_contents(__DIR__ . '/sample/USPTO/publication.sample'));

        return $USPTO->getSearchResult();
    }

    private function getExpectedPublication()
    {
        $result = new IPRight();
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
        $inventors = new \SNicholson\IPFO\Parties\Party();
        $inventor1 = new \SNicholson\IPFO\Parties\Inventor();
        $inventor1->setName('Goth; Gary F.');
        $inventor1->setSequence(1);
        $inventors->addMember($inventor1);
        $inventor2 = new \SNicholson\IPFO\Parties\Inventor();
        $inventor2->setName('Kearney; Daniel J.');
        $inventor2->setSequence(2);
        $inventors->addMember($inventor2);
        $inventor3 = new \SNicholson\IPFO\Parties\Inventor();
        $inventor3->setName('Makowicki; Robert P.');
        $inventor3->setSequence(3);
        $inventors->addMember($inventor3);
        $inventor4 = new \SNicholson\IPFO\Parties\Inventor();
        $inventor4->setName('McClafferty; W. David');
        $inventor4->setSequence(4);
        $inventors->addMember($inventor4);
        $inventor5 = new \SNicholson\IPFO\Parties\Inventor();
        $inventor5->setName('Porter; Donald W.');
        $inventor5->setSequence(5);
        $inventors->addMember($inventor5);
        $result->setInventors($inventors);
        //Applicants
        $applicant = new \SNicholson\IPFO\Parties\Applicant();
        $applicant->setName('International Business Machines Corporation');
        $applicant->setSequence(1);
        $applicants = new \SNicholson\IPFO\Parties\Party();
        $applicants->addMember($applicant);
        $result->setApplicants($applicants);

        $this->getPatentCitationsForPublicationSample($result);

        $this->getNonPatentLiteraturePublicationSample($result);

        return $result;
    }

    private function getNonPatentLiteraturePublicationSample(IPRightInterface &$result)
    {
        $npl = [
            ['text' => '0 148 102', 'date' => 'Jul 1985', 'country' => 'EP'],
            ['text' => '9-280696', 'date' => 'Oct 1997', 'country' => 'JP'],
            ['text' => '11-108228', 'date' => 'Apr 1999', 'country' => 'JP']
        ];
        foreach($npl as $citation) {
            $result->addCitation(Citation::nonPatentLiterature($citation['text'], null, $citation['country'], $citation['date']));
        }
    }

    /**
     * @param $result
     * @return mixed
     */
    private function getPatentCitationsForPublicationSample(IPRightInterface &$result)
    {
        //Citations
        $patentCitations = [
            ['number' => '4986085', 'date' => 'January 1991', 'author' => 'Tischer'],
            ['number' => '5502970', 'date' => 'April 1996', 'author' => 'Rajendran'],
            ['number' => '5694782', 'date' => 'December 1997', 'author' => 'Alsenz'],
            ['number' => '5771703', 'date' => 'June 1998', 'author' => 'Rajendran'],
            ['number' => '5791155', 'date' => 'August 1998', 'author' => 'Tulpule'],
            ['number' => '6121735', 'date' => 'September 2000', 'author' => 'Igeta et al.'],
            ['number' => '6182742', 'date' => 'February 2001', 'author' => 'Takahashi et al.'],
            ['number' => '6272870', 'date' => 'August 2001', 'author' => 'Schaeffer'],
            ['number' => '6595018', 'date' => 'July 2003', 'author' => 'Goth et al.']
        ];
        foreach ($patentCitations as $citation) {
            $result->addCitation(Citation::patent($citation['number'], 'US', null, $citation['date']));
        }

        return $result;
    }
}
