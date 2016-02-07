<?php

namespace SNicholson\IPFO\Parties;

class Inventor extends PartyMember
{

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $name       = rtrim($name, ",");
        $this->name = $name;
    }
}