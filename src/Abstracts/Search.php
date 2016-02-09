<?php

namespace SNicholson\IPFO\Abstracts;

use SNicholson\IPFO\Containers\RequestsContainer;
use SNicholson\IPFO\Interfaces\SearchInterface;
use WorkAnyWare\IPFO\IPRight;
use WorkAnyWare\IPFO\IPRightInterface;
use WorkAnyWare\IPFO\IPRights\Number;
use WorkAnyWare\IPFO\IPRights\SearchSource;

abstract class Search implements SearchInterface
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
     * @return bool|IPRightInterface
     */
    public function numberSearch(Number $number)
    {
        //Set up some variables
        $this->requestNumber     = $number->getNumberString();
        $this->requestNumberType = $number->getType();

        //Find the official office we should search from the number format
        $this->searchObj = $this->findOfficeFromNumber($this->requestNumber);

        if ($this->searchObj) {
            if ($result = $this->searchObj->simpleNumberSearch($this->requestNumber, $this->requestNumberType)) {
                return $result;
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

    abstract protected function findOfficeFromNumber($number);
}
