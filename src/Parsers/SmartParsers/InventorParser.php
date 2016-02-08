<?php

namespace SNicholson\IPFO\Parsers\SmartParsers;

use SNicholson\IPFO\Parties\Inventor;

class InventorParser extends PartyMemberParser
{
    protected $memberTypeName = ['inventor', 'ep-inventor'];
    protected $memberTypeNames = ['inventors'];
    protected $memberClass = Inventor::class;
}