<?php

use SNicholson\IPFO\Parsers\Document;
use SNicholson\IPFO\Parsers\EPOLine\EPOXMLParser;
use SNicholson\IPFO\Parties\Agent;
use SNicholson\IPFO\Parties\Applicant;
use SNicholson\IPFO\Parties\Inventor;
use SNicholson\IPFO\Parties\Party;
use SNicholson\IPFO\Parties\PartyMemberAddress;
use SNicholson\IPFO\ValueObjects\Priority;
use SNicholson\IPFO\ValueObjects\SearchType;

class EPOXMLParserTest extends PHPUnit_Framework_TestCase
{

    public function testParsingOfBelgiumApplication()
    {
        //Make the Right and set basic information
        $IPRight = new \SNicholson\IPFO\IPRight();
        $IPRight->setFrenchTitle('Méthode et dispositif de mesure de la direction de sources RF');
        $IPRight->setLanguageOfFiling('FR');
        $IPRight->setSource(SearchType::EPO());

        $applicants = new Party();
        $applicant = new Applicant();
        $applicant->setName('RAUCY, Christopher');
        $applicant->setPhone('01373 462720');
        $applicant->setFax('FAX');
        $applicant->setNationality('British');
        $applicantAddress = new PartyMemberAddress();
        $applicantAddress->setAddress("Boucle de Roncevaux, 6 bte 201, Louvain-la-Neuve");
        $applicantAddress->setPostCode('1348');
        $applicantAddress->setCountry('BE');
        $applicant->setAddress($applicantAddress);
        $applicants->addMember($applicant);
        $IPRight->setApplicants($applicants);

        $agent = new Agent();
        $agent->setName('PECHER, Nicolas');
        $agent->setPhone('010/392257');
        $agent->setFax('010/816308');
        $agent->setEmail('nicolas.pecher@pecherandparners.be');
        $agentAddress = new PartyMemberAddress();
        $agentAddress->setCountry('BE');
        $agentAddress->setAddress("Pecher & Partners, Rue Louis de Geer 6, Louvain-la-Neuve");
        $agentAddress->setPostCode('1348');
        $agent->setAddress($agentAddress);
        $agent->setReference('T0026-BE-P');
        $party = new Party();
        $party->addMember($agent);

        $IPRight->setAgents($party);

        $document = new Document(file_get_contents(__DIR__ . '/sample/be-request.xml'), 'be-request-xml');
        $EPOXmlParser = new EPOXMLParser();
        $EPOXmlParser->setDocument($document);
        $this->assertEquals(
            $IPRight,
            $EPOXmlParser->getIPRight()
        );
    }

    public function testParsingEPApplication()
    {
        //Make the Right and set basic information
        $IPRight = new \SNicholson\IPFO\IPRight();
        $IPRight->setEnglishTitle('High current cyclotron');
        $IPRight->setLanguageOfFiling('GB');
        $IPRight->setSource(SearchType::EPO());

        $applicants = new Party();
        $applicant = new Applicant();
        $applicant->setName('Ion Beam Applications S.A.');
        $applicantAddress = new PartyMemberAddress();
        $applicantAddress->setAddress("Chemin du Cyclotron 3, Louvain-la-Neuve");
        $applicantAddress->setPostCode('1348');
        $applicantAddress->setCountry('BE');
        $applicant->setAddress($applicantAddress);
        $applicants->addMember($applicant);
        $IPRight->setApplicants($applicants);

        $inventors = new Party();
        $inventor1 = new Inventor();
        $inventor1->setName('FORTON, Eric');
        $inventor1->getAddress()->setAddress("Chemin Mahy 110, Nil-Pierreux");
        $inventor1->getAddress()->setPostCode('1457');
        $inventor1->getAddress()->setCountry('BE');
        $inventors->addMember($inventor1);
        $inventor2 = new Inventor();
        $inventor2->setName('KLEEVEN, Willem');
        $inventor2->getAddress()->setAddress("Zavelstraat 17, Pellenberg");
        $inventor2->getAddress()->setPostCode('3212');
        $inventor2->getAddress()->setCountry('BE');
        $inventors->addMember($inventor2);
        $IPRight->setInventors($inventors);

        $agent1 = new Agent();
        $agent1->setName('PECHER, Nicolas');
        $agent1->setPhone('+32 10 39 22 57');
        $agent1->setFax('');
        $agent1->setEmail('nicolas.pecher@pecherandpartners.be');
        $agent1->getAddress()->setCountry('BE');
        $agent1->getAddress()->setAddress("Pecher & Partners, Rue Louis de Geer 6, Louvain-la-Neuve");
        $agent1->getAddress()->setPostCode('1348');
        $agent1->setReference('T0021-EP2');
        $party = new Party();
        $party->addMember($agent1);

        $agent2 = new Agent();
        $agent2->setName('CONNOR, Marco');
        $agent2->setPhone('+32 10 39 22 57');
        $agent2->setFax('');
        $agent2->setEmail('marco.connor@pecherandpartners.be');
        $agent2->getAddress()->setCountry('BE');
        $agent2->getAddress()->setAddress("Pecher & Partners, Rue Louis de Geer 6, Louvain-la-Neuve");
        $agent2->getAddress()->setPostCode('1348');
        $agent2->setReference('T0021-EP2');

        $party->addMember($agent2);

        $IPRight->setAgents($party);

        $priority = Priority::fromNumber('EP14193792.0');
        $priority->setDate('2014-11-19');
        $priority->setCountry('EP');

        $IPRight->addPriority($priority);

        $document = new Document(file_get_contents(__DIR__ . '/sample/ep-request.xml'), 'be-request-xml');
        $EPOXmlParser = new EPOXMLParser();
        $EPOXmlParser->setDocument($document);
        $this->assertEquals(
            $IPRight,
            $EPOXmlParser->getIPRight()
        );
    }


    public function testParsingWOApplication()
    {
        //Make the Right and set basic information
        $IPRight = new \SNicholson\IPFO\IPRight();
        $IPRight->setFrenchTitle('Appareil pour la fabrication de cigarettes');
        $IPRight->setLanguageOfFiling('FR');
        $IPRight->setSource(SearchType::EPO());

        $applicants = new Party();
        $applicant = new Applicant();
        $applicant->setName('Atelier Neycken sprl');
        $applicantAddress = new PartyMemberAddress();
        $applicantAddress->setAddress("Sur le Tombeux 30, Andrimont");
        $applicantAddress->setPostCode('4821');
        $applicantAddress->setCountry('BE');
        $applicant->setAddress($applicantAddress);
        $applicant->setNationality('BE');
        $applicants->addMember($applicant);
        $IPRight->setApplicants($applicants);

        $inventors = new Party();
        $inventor1 = new Inventor();
        $inventor1->setName('Neycken, Alain');
        $inventor1->getAddress()->setAddress("Atelier Neycken, Sur le Tombeux 30, Andrimont");
        $inventor1->getAddress()->setPostCode('4821');
        $inventor1->getAddress()->setCountry('BE');
        $inventors->addMember($inventor1);
        $IPRight->setInventors($inventors);

        $agent1 = new Agent();
        $agent1->setName('Pecher, Nicolas');
        $agent1->setPhone('+32 477 526 893');
        $agent1->setFax('');
        $agent1->setEmail('nicolas.pecher123@gmail.com');
        $agent1->getAddress()->setCountry('BE');
        $agent1->getAddress()->setAddress("Pecher Patents sprl, 50 rue des trois burettes, Mont Saint Guibert");
        $agent1->getAddress()->setPostCode('1435');
        $agent1->setReference('T0004 - WO');
        $party = new Party();
        $party->addMember($agent1);

        $agent2 = new Agent();
        $agent2->setName('CONNOR, Marco');
        $agent2->setPhone('+32 494 05 19 66');
        $agent2->setFax('');
        $agent2->setEmail('mconnor0506@yahoo.fr');
        $agent2->getAddress()->setCountry('BE');
        $agent2->getAddress()->setAddress("Tarameera sprl, Rue Marie Gevers 4, Louvain-la-Neuve");
        $agent2->getAddress()->setPostCode('1348');
        $agent2->setReference('T0004 - WO');
        $party->addMember($agent2);

        $IPRight->setAgents($party);

        $priority = Priority::fromNumber('BE2014/0647');
        $priority->setDate('2014-08-28');
        $priority->setCountry('BE');

        $IPRight->addPriority($priority);

        $document = new Document(file_get_contents(__DIR__ . '/sample/wo-request.xml'), 'be-request-xml');
        $EPOXmlParser = new EPOXMLParser();
        $EPOXmlParser->setDocument($document);
        $this->assertEquals(
            $IPRight,
            $EPOXmlParser->getIPRight()
        );
    }
}
