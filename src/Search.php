<?php

namespace SNicholson\IPFO;

use InvalidArgumentException;
use SNicholson\IPFO\Abstracts\Controller;
use SNicholson\IPFO\Controllers\PatentController;
use SNicholson\IPFO\Controllers\TrademarkController;
use SNicholson\IPFO\Interfaces\ControllerInterface;

/**
 * Class Search
 * @package SNicholson\IPFO
 */
class Search
{

    /**
     * @var
     */
    private $IPType;
    /**
     * @var
     */
    private $NumberType;
    /**
     * @var
     */
    private $number;
    /**
     * @var array
     */
    private $NumberTypes = ['application', 'publication'];
    /** @var $results SearchResponseCollection */
    private $results = [];
    /**
     * @var
     */
    private $success;
    /**
     * @var
     */
    private $dataSource;

    /**
     * Instantiates a new search for a trademark
     * @return Search
     *
     */
    public static function tradeMark()
    {
        return new Search('Trademark');
    }

    /**
     * Instantiates a new search for a patent
     * @return Search
     *
     */
    public static function patent()
    {
        return new Search('Patent');
    }

    /**
     * Sets the IP type for the Search, e.g. Patent or Trademark
     * @param string $IPType
     *
     */
    private function __construct($IPType)
    {
        $this->IPType = $IPType;
    }

    public function byApplicationNumber($number)
    {

    }

    /**
     * Sets the Number type for the search e.g. application or publication
     *
     * @param mixed $NumberType
     *
     * @return $this
     */
    public function setNumberType($NumberType)
    {
        if (!in_array($NumberType, $this->NumberTypes)) {
            throw new InvalidArgumentException(
                "Invalid Number Type was specified, should be one of " . json_encode($this->NumberTypes)
            );
        }
        $this->NumberType = $NumberType;
        return $this;
    }

    /**
     * Sets the number to be used for the search e.g. EP1234567
     *
     * @param mixed $number
     *
     * @return $this
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Search the Official Offices for the information regarding the number you entered
     * return void
     */
    public function search()
    {

        //Write the class name into a variable
        $searchObj = 'WAL\\IPFO\\Controllers\\' . $this->IPType . 'Controller';

        if (!class_exists($searchObj)) {
            throw new InvalidArgumentException("Class for search was not found, class was " . $searchObj);
        }

        //Instantiate a new Search class
        /** @var Controller $searchClass */
        $searchClass = new $searchObj();
        $numberType  = $this->NumberType;

        //Run the search
        if ($searchClass->numberSearch($this->number, $this->NumberType)) {
            $this->results = $searchClass->getResultCollection();
            $this->dataSource = $searchClass->getSearchSource();
            $this->results->setSuccess(true);
        } else {
            $this->results = $searchClass->getResultCollection();
            $this->results->setSuccess(false);
        }


        return $this;
    }

    /**
     * Get the Standardised Map of results from the official offices
     * return SearchResponseCollection
     */
    public function getResultCollection()
    {
        return $this->results;
    }

} 