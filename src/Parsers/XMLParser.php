<?php

namespace SNicholson\IPFO\Parsers;

use SNicholson\IPFO\IPRight;
use SNicholson\IPFO\Parsers\EPOLine\EPOXMLParser;

class XMLParser implements ParserInterface
{
    /**
     * @var Document
     */
    private $document;

    /**
     * Set the document which is to be parsed by the parser
     *
     * @param Document $document
     *
     * @return mixed
     */
    public function setDocument(Document $document)
    {
        if ($document->getExtension() !== 'xml') {
            throw new \InvalidArgumentException("XML Parser asked to parse non XML document " . $document->getFilename());
        }
        $this->document = $document;
    }

    /**
     * Return the IP Right which has been parsed from the document
     * @return IPRight
     */
    public function getIPRight()
    {
        //TODO find some way to identify which XML file this is
        $EPOLineParser = new EPOXMLParser();
        $EPOLineParser->setDocument($this->document);
        return $EPOLineParser->getIPRight();
    }
}