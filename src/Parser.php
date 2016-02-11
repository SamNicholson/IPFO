<?php

namespace SNicholson\IPFO;

use SNicholson\IPFO\Parsers\Document;
use SNicholson\IPFO\Parsers\ParserInterface;
use SNicholson\IPFO\Parsers\XMLParser;
use WorkAnyWare\IPFO\IPRightInterface;

/**
 * Class Parser
 * @package SNicholson\IPFO
 */
class Parser
{
    /**
     * Parses a given document into an IPF Object
     *
     * @param $document
     *
     * @return IPRightInterface
     */
    public static function document(Document $document)
    {
        switch ($document->getExtension()) {
            case 'xml':
                return self::locateXMLDocumentParser($document);
                break;
        }
        return false;
    }

    /**
     * @param $document
     *
     * @return ParserInterface
     */
    private static function locateXMLDocumentParser(Document $document)
    {
        $parser = new XMLParser();
        $parser->setDocument($document);
        return $parser->getIPRight();
    }
}