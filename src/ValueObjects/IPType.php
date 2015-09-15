<?php

namespace SNicholson\IPFO\ValueObjects;

class IPType
{

    const TRADEMARK = 'TradeMark';
    const PATENT = 'Patent';

    private $IPType;

    public static function tradeMark()
    {
        return new IPType(IPType::TRADEMARK);
    }

    public static function patent()
    {
        return new IPType(IPType::PATENT);
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
