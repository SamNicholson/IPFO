<?php

namespace SNicholson\IPFO\Searches;

use SNicholson\IPFO\Containers\RequestsContainer;
use SNicholson\IPFO\Interfaces\SearchInterface;
use SNicholson\IPFO\ValueObjects\Number;
use SNicholson\IPFO\ValueObjects\SearchType;

class OfficeSearch implements SearchInterface
{

    /**
     * @var RequestsContainer
     */
    private $container;
    /**
     * @var
     */
    private $office;
    private $error;
    private $searchObj;
    private $requestNumber;
    private $requestNumberType;

    public function __construct(RequestsContainer $container, $office)
    {
        $this->container = $container;
        $this->office = $office;
    }

    public function getError()
    {
        return $this->error;
    }

    public function numberSearch(Number $number)
    {
        //Set up some variables
        $this->requestNumber     = $number->getNumberString();
        $this->requestNumberType = $number->getType();

        //Find the official office we should search from the number format
        switch ($this->office) {
            case SearchType::EPO:
                $this->searchObj = $this->container->newEPORequest();
                break;
            case SearchType::USPTO:
                $this->searchObj = $this->container->newUSPTORequest();
                break;
            case SearchType::WIPO:
                $this->searchObj = $this->container->newWIPORequest();
                break;
        }

        if ($this->searchObj) {
            if ($result = $this->searchObj->simpleNumberSearch($this->requestNumber, $this->requestNumberType)) {
                return $result;
            } else {
                $this->error = $this->searchObj->getError();
                return false;
            }
        } else {
            $this->error = "Invalid search requested";
            return false;
        }
    }
}
