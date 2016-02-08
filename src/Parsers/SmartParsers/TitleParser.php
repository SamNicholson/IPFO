<?php

namespace SNicholson\IPFO\Parsers\SmartParsers;

use SNicholson\IPFO\Helpers\IPFOXML;
use SNicholson\IPFO\Parsers\RecursiveFieldSearch;

class TitleParser
{

    public static function english(IPFOXML $XML)
    {
        if (self::getLanguageOfFiling($XML) == 'GB') {
            return RecursiveFieldSearch::searchByNameArray(['english-title', 'title', 'invention-title'], $XML);
        } else {
            return RecursiveFieldSearch::searchByNameArray(['english-title'], $XML);
        }
    }

    public static function french(IPFOXML $XML)
    {
        if (self::getLanguageOfFiling($XML) == 'FR') {
            return RecursiveFieldSearch::searchByNameArray(['french-title', 'title', 'invention-title'], $XML);
        } else {
            return RecursiveFieldSearch::searchByNameArray(['french-title'], $XML);
        }
    }

    public static function german(IPFOXML $XML)
    {
        if (self::getLanguageOfFiling($XML) == 'DE') {
            return RecursiveFieldSearch::searchByNameArray(['german-title', 'title', 'invention-title'], $XML);
        } else {
            return RecursiveFieldSearch::searchByNameArray(['german-title'], $XML);
        }
    }

    public static function getLanguageOfFiling(IPFOXML $XML)
    {
        $language = RecursiveFieldSearch::searchByNameArray(
            ['language-of-filing', 'ep-language-of-filing', 'language'],
            $XML
        );
        if (in_array(strtolower($language), ['french', 'fr'])) {
            return 'FR';
        }
        if (in_array(strtolower($language), ['english', 'gb', 'uk', 'en'])) {
            return 'GB';
        }
        if (in_array(strtolower($language), ['german', 'de'])) {
            return 'DE';
        }
        return null;
    }
}
