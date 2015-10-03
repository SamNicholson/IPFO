<?php

use SNicholson\IPFO\Result;
use SNicholson\IPFO\ValueObjects\Applicant;
use SNicholson\IPFO\ValueObjects\Inventor;
use SNicholson\IPFO\ValueObjects\Party;
use SNicholson\IPFO\ValueObjects\Priority;
use SNicholson\IPFO\ValueObjects\SearchSource;

class ResultTest extends PHPUnit_Framework_TestCase
{

    public function testToArrayWorks()
    {
        $this->assertEquals($this->getSampleArray(), $this->getSampleResult()->toArray());
    }

    private function getSampleArray()
    {
        //Setup the expected publication result
        $result = [];
        $result['source'] = SearchSource::EPO()->__toString();

        //titles
        $result['titles']['english'] =
            'NICKING AND EXTENSION AMPLIFICATION REACTION FOR THE EXPONENTIAL AMPLIFICATION OF NUCLEIC ACIDS';
        $result['titles']['french'] =
            'RÉACTION D\'AMPLIFICATION DE SYNCHRONISATION ET D\'EXTENSION POUR L\'AMPLIFICATION ' .
            'EXPONENTIELLE D\'ACIDES NUCLÉIQUES';
        $result['titles']['german'] =
            'NICKING- UND VERLÄNGERUNGS-AMPLIFIKATIONSREAKTION ZUR EXPONENTIELLEN AMPLIFIKATION VON NUKLEINSÄUREN';

        //application information
        $result['application']['country'] = 'EP';
        $result['application']['date'] = '2008-07-14';
        $result['application']['number'] = '08781827';

        //publication information
        $result['publication']['country'] = 'EP';
        $result['publication']['date'] = '2010-05-05';
        $result['publication']['number'] = '2181196';

        //grant information
        $result['grant']['country'] = 'JP';
        $result['grant']['date'] = '2015-01-01';
        $result['grant']['number'] = '12345678';

        //Priorities
        $result['priorities'][0] = [
            'number' => 'WO2008US70023',
            'date'   => '2008-07-14',
            'country' => 'GB',
            'kind'   => '1'
        ];
        $result['priorities'][1] = [
            'number'  => 'US20070778018',
            'date'    => '2007-07-14',
            'country' => 'GB',
            'kind'    => '2'
        ];

        //Applicants
        $result['applicants'] = [
            ['name' => 'IONIAN TECHNOLOGIES, INC', 'sequence' => '1']
        ];

        //Inventors
        $result['inventors'] = [
            ['name' => 'MAPLES, BRIAN, K,', 'sequence' => '1'],
            ['name' => 'HOLMBERG, REBECCA, C,', 'sequence' => '2'],
            ['name' => 'MILLER, ANDREW, P,', 'sequence' => '3'],
            ['name' => 'PROVINS, JARROD,', 'sequence' => '4'],
            ['name' => 'ROTH, RICHARD,', 'sequence' => '5'],
            ['name' => 'MANDELL, JEFFREY', 'sequence' => '6'],
        ];
        return $result;
    }

    private function getSampleResult()
    {
        //Setup the expected publication result
        $result = new Result();
        $result->setSource(SearchSource::EPO());

        //titles
        $result->setEnglishTitle(
            'NICKING AND EXTENSION AMPLIFICATION REACTION FOR THE EXPONENTIAL AMPLIFICATION OF NUCLEIC ACIDS'
        );
        $result->setFrenchTitle(
            'RÉACTION D\'AMPLIFICATION DE SYNCHRONISATION ET D\'EXTENSION POUR L\'AMPLIFICATION ' .
            'EXPONENTIELLE D\'ACIDES NUCLÉIQUES'
        );
        $result->setGermanTitle(
            'NICKING- UND VERLÄNGERUNGS-AMPLIFIKATIONSREAKTION ZUR EXPONENTIELLEN AMPLIFIKATION VON NUKLEINSÄUREN'
        );

        //application information
        $result->setApplicationCountry('EP');
        $result->setApplicationDate('2008-07-14');
        $result->setApplicationNumber('08781827');

        //publication information
        $result->setPublicationCountry('EP');
        $result->setPublicationDate('2010-05-05');
        $result->setPublicationNumber('2181196');

        //grant information
        $result->setGrantCountry('JP');
        $result->setGrantDate('2015-01-01');
        $result->setGrantNumber('12345678');

        //Priorities
        $firstPriority = Priority::fromNumber('WO2008US70023');
        $firstPriority->setDate('2008-07-14');
        $firstPriority->setCountry('GB');
        $firstPriority->setKind('1');
        $secondPriority = Priority::fromNumber('US20070778018');
        $secondPriority->setDate('2007-07-14');
        $secondPriority->setCountry('GB');
        $secondPriority->setKind('2');
        $result->setPriorities($firstPriority, $secondPriority);

        //Applicants
        $applicantParty = new Party();
        $applicant1 = new Applicant();
        $applicant1->setName('IONIAN TECHNOLOGIES, INC');
        $applicant1->setSequence('1');
        $applicantParty->addMember($applicant1);
        $result->setApplicants($applicantParty);

        //Inventors
        $inventorParty = new Party();
        $inventor1 = new Inventor();
        $inventor1->setName('MAPLES, BRIAN, K,');
        $inventor1->setSequence('1');
        $inventorParty->addMember($inventor1);
        $inventor2 = new Inventor();
        $inventor2->setName('HOLMBERG, REBECCA, C,');
        $inventor2->setSequence('2');
        $inventorParty->addMember($inventor2);
        $inventor3 = new Inventor();
        $inventor3->setName('MILLER, ANDREW, P,');
        $inventor3->setSequence('3');
        $inventorParty->addMember($inventor3);
        $inventor4 = new Inventor();
        $inventor4->setName('PROVINS, JARROD,');
        $inventor4->setSequence('4');
        $inventorParty->addMember($inventor4);
        $inventor5 = new Inventor();
        $inventor5->setName('ROTH, RICHARD,');
        $inventor5->setSequence('5');
        $inventorParty->addMember($inventor5);
        $inventor6 = new Inventor();
        $inventor6->setName('MANDELL, JEFFREY');
        $inventor6->setSequence('6');
        $inventorParty->addMember($inventor6);
        $result->setInventors($inventorParty);

        return $result;
    }

}
