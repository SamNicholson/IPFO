<?php

namespace SNicholson\IPFO\Parsers;

use SNicholson\IPFO\IPRight;

interface ParserInterface
{
    /**
     * Set the document which is to be parsed by the parser
     * @param Document $document
     *
     * @return mixed
     */
    public function setDocument(Document $document);

    /**
     * Return the IP Right which has been parsed from the document
     * @return IPRight
     */
    public function getIPRight();
}