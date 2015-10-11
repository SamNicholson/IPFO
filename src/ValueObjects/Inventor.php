<?php

namespace SNicholson\IPFO\ValueObjects;

use SNicholson\IPFO\Interfaces\PartyMemberInterface;

class Inventor implements PartyMemberInterface
{

    private $name;
    private $sequence;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $name = rtrim($name, ",");
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param mixed $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }
}