<?php

namespace SNicholson\IPFO;

use SNicholson\IPFO\Parsers\Document;
use SNicholson\IPFO\Parsers\ParserInterface;
use SNicholson\IPFO\Parsers\XMLParser;

/**
 * Class ParserQuery
 * @package SNicholson\IPFO
 */
class ParserQuery
{
    /**
     * @param $document
     *
     * @return IPRight
     */
    public static function locateParserForDocument(Document $document)
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
    public static function locateXMLDocumentParser(Document $document)
    {
        $parser = new XMLParser();
        $parser->setDocument($document);
        return $parser->getIPRight();
    }
}