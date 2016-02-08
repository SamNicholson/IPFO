<?php

namespace SNicholson\IPFO\Parsers\EPOLine;

use SNicholson\IPFO\Helpers\IPFOXML;
use SNicholson\IPFO\IPRight;
use SNicholson\IPFO\Parsers\Document;
use SNicholson\IPFO\Parsers\ParserInterface;
use SNicholson\IPFO\Parsers\SmartParsers\AgentParser;
use SNicholson\IPFO\Parsers\SmartParsers\ApplicantParser;
use SNicholson\IPFO\Parsers\SmartParsers\InventorParser;
use SNicholson\IPFO\Parsers\SmartParsers\PrioritiesParser;
use SNicholson\IPFO\Parsers\SmartParsers\TitleParser;

class EPOXMLParser implements ParserInterface
{
    private $XML;

    /**
     * Set the document which is to be parsed by the parser
     *
     * @param Document $document
     *
     * @return mixed
     */
    public function setDocument(Document $document)
    {
        $this->XML = simplexml_load_string($document->getContent(), IPFOXML::class);
    }

    /**
     * Return the IP Right which has been parsed from the document
     * @return IPRight
     */
    public function getIPRight()
    {
        $IPRight = new IPRight();
        $IPRight->setApplicants(ApplicantParser::parse($this->XML));
        $IPRight->setInventors(InventorParser::parse($this->XML));
        $IPRight->setAgents(AgentParser::parse($this->XML));
        $IPRight->setEnglishTitle(TitleParser::english($this->XML));
        $IPRight->setFrenchTitle(TitleParser::french($this->XML));
        $IPRight->setGermanTitle(TitleParser::german($this->XML));
        $IPRight->setLanguageOfFiling(TitleParser::getLanguageOfFiling($this->XML));
        PrioritiesParser::parse($this->XML, $IPRight);
        return $IPRight;
    }

}