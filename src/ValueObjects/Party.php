<?php

namespace SNicholson\IPFO\ValueObjects;

use SNicholson\IPFO\Interfaces\PartyMemberInterface;

class Party
{
    private $members;

    public function addMember(PartyMemberInterface $partyMember)
    {
        $this->members[] = $partyMember;
    }

    public function setMembers(PartyMemberInterface ...$partyMembers)
    {
        $this->members = $partyMembers;
    }

    public function getMembers()
    {
        return $this->members;
    }
}