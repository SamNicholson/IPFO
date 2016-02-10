<?php

namespace SNicholson\IPFO\Interfaces;

use SNicholson\IPFO\Helpers\RightNumber;
use WorkAnyWare\IPFO\IPRightInterface;
use SNicholson\IPFO\Searches\SearchError;

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
     * @param RightNumber $number
     *
     * @return false|IPRightInterface
     */
    public function numberSearch(RightNumber $number);
}
