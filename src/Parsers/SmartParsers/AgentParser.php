<?php

namespace SNicholson\IPFO\Parsers\SmartParsers;

use WorkAnyWare\IPFO\Parties\Agent;

class AgentParser extends PartyMemberParser
{
    protected $memberTypeName = ['agent', 'ep-agent'];
    protected $memberTypeNames = ['agents'];
    protected $memberClass = Agent::class;
}