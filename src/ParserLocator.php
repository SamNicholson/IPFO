<?php

namespace SNicholson\IPFO;

use SNicholson\IPFO\Parsers\Document;
use SNicholson\IPFO\Parsers\ParserInterface;

/**
 * Class ParserLocator
 * @package SNicholson\IPFO
 */
class ParserLocator
{
    /**
     * @param $document
     *
     * @return ParserInterface
     */
    public static function locateParserForDocument(Document $document)
    {
        switch ($document->getExtension()) {
            case 'xml':
                return self::locateXMLDocumentParser($document);
                break;
        }
    }

    /**
     * @param $document
     *
     * @return ParserInterface
     */
    public static function locateXMLDocumentParser(Document $document)
    {

    }
}