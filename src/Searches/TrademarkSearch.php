<?php

namespace SNicholson\IPFO\Searches;

use SNicholson\IPFO\Abstracts\Search;
use SNicholson\IPFO\Interfaces\SearchInterface;
use WorkAnyWare\IPFO\IPRights\Number;

class TrademarkSearch extends Search implements SearchInterface
{

    protected function findOfficeFromNumber($number)
    {
        //TODO massively improve this, to include fantastic validation etc.
        if (substr($number, 0, 2) == 'US' && $this->requestNumberType == Number::APPLICATION) {
            return $this->requestsContainer->newUSPTOTrademarkRequest();
        }
        return $this->requestsContainer->newUSPTOTrademarkRequest();
    }
}
