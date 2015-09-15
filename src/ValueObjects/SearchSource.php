<?php

namespace SNicholson\IPFO\ValueObjects;

class SearchSource
{

    private $source;

    public static function EPO()
    {
        return new SearchSource('EPO');
    }

    public static function USPTO()
    {
        return new SearchSource('USPTO');
    }

    public static function WIPO()
    {
        return new SearchSource('WIPO');
    }

    private function __construct($source)
    {
        $this->source = $source;
    }

    public function __toString()
    {
        return $this->source;
    }
}