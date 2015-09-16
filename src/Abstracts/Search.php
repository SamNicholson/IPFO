<?php

namespace SNicholson\IPFO\Abstracts;

use SNicholson\IPFO\Containers\RequestsContainer;
use SNicholson\IPFO\ValueObjects\Number;
use SNicholson\IPFO\ValueObjects\SearchSource;

abstract class Search
{

    protected $requestsContainer;
    protected $requestNumber;
    protected $requestNumberType;
    /** @var $searchObj Request */
    protected $searchObj;


    protected $error;

    public function __construct(RequestsContainer $requestsContainer)
    {
        $this->requestsContainer = $requestsContainer;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param Number $number
     *
     * @return bool
     */
    public function numberSearch(Number $number)
    {
        //Set up some variables
        $this->requestNumber     = $number->getNumberString();
        $this->requestNumberType = $number->getType();

        //Find the official office we should search from the number format
        $this->searchObj = $this->findOfficeFromNumber($this->requestNumber);

        if ($this->searchObj) {
            if ($this->searchObj->simpleNumberSearch($this->requestNumber, $this->requestNumberType)) {
                return true;
            } else {
                $this->error = $this->searchObj->getError();
                return false;
            }
        } else {
            $this->error = "We are unable to search on the format of the number specified, we did not match the format";
            return false;
        }

    }

    /**
     * @return SearchSource
     */
    public function getSearchSource()
    {
        return $this->searchObj->getDataSource();
    }

    /**
     * @return \SNicholson\IPFO\ResultCollection
     */
    public function getResultCollection()
    {
        return $this->searchObj->getResponse();
    }

    protected function findOfficeFromNumber($number)
    {
        return false;
    }
}
