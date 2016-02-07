<?php

namespace SNicholson\IPFO\Interfaces;

use SNicholson\IPFO\IPRight;
use SNicholson\IPFO\Searches\SearchError;
use SNicholson\IPFO\ValueObjects\Number;

interface SearchInterface
{

    /**
     * Returns an instance of search error or false
     * @return SearchError|false
     */
    public function getError();

    /**
     * Returns the results of a number search via this search interface
     *
*@param Number $number
     *
*@return false|IPRight
     */
    public function numberSearch(Number $number);
}
