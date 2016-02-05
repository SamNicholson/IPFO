<?php

namespace SNicholson\IPFO\Searches;

use SNicholson\IPFO\Abstracts\Search;
use SNicholson\IPFO\Interfaces\SearchInterface;

class PatentSearch extends Search
{

    protected $requestNumber = '';
    protected $error;

    protected function findOfficeFromNumber($number)
    {
        if (substr($number, 0, 2) == 'EP') {
            return $this->requestsContainer->newEPORequest();
        } elseif (substr($number, 0, 2) == 'US') {
            return $this->requestsContainer->newUSPTORequest();
        } else {
            return $this->requestsContainer->newWIPORequest();
        }
    }


}