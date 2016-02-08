<?php

namespace SNicholson\IPFO\Parsers\EPOLine;

use SNicholson\IPFO\Helpers\SimpleXMLToArray;
use SNicholson\IPFO\IPRight;
use SNicholson\IPFO\Parsers\Document;
use SNicholson\IPFO\Parsers\ParserInterface;
use SNicholson\IPFO\Parsers\SmartParsers\ApplicantParser;

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
        $this->XML = simplexml_load_string($document->getContent());
        $this->XML = SimpleXMLToArray::convert($this->XML);
    }

    /**
     * Return the IP Right which has been parsed from the document
     * @return IPRight
     */
    public function getIPRight()
    {
        $IPRight = new IPRight();
        $IPRight->setApplicants(ApplicantParser::parse((array) $this->XML));
        return new IPRight();
    }

}