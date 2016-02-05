<?php

namespace SNicholson\IPFO\ValueObjects;

class SearchType
{

    const TRADEMARK = 'TradeMark';
    const PATENT = 'Patent';
    const EPO = 'EPO';
    const WIPO = 'WIPO';
    const USPTO = 'USPTO';

    private $IPType;

    public static function tradeMark()
    {
        return new SearchType(SearchType::TRADEMARK);
    }

    public static function patent()
    {
        return new SearchType(SearchType::PATENT);
    }

    public static function EPO()
    {
        return new SearchType(SearchType::EPO);
    }

    public static function WIPO()
    {
        return new SearchType(SearchType::WIPO);
    }

    public static function USPTO()
    {
        return new SearchType(SearchType::USPTO);
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
