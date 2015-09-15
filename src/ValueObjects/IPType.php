<?php

namespace SNicholson\IPFO\ValueObjects;

class IPType
{

    private $IPType;

    public static function tradeMark()
    {
        return new IPType('TradeMark');
    }

    public static function patent()
    {
        return new IPType('Patent');
    }

    private function __construct($IPType)
    {
        $this->IPType = $IPType;
    }

    public function __toString()
    {
        return $this->IPType;
    }
}
