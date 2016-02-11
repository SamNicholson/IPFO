<?php

namespace SNicholson\IPFO\Parsers;

use WorkAnyWare\IPFO\IPF;
use WorkAnyWare\IPFO\IPRightInterface;

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
     * @return IPRightInterface
     */
    public function getIPRight();
}