<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 01/02/2015
 * Time: 16:00
 */

namespace WAL\IPFO;

use InvalidArgumentException;
use WAL\IPFO\Abstracts\Controller;
use WAL\IPFO\Controllers\PatentController;
use WAL\IPFO\Controllers\TrademarkController;
use WAL\IPFO\Interfaces\ControllerInterface;

class IPFOSearch {

    private $IPType;
    private $NumberType;
    private $number;

    private $IPTypes = array('Patent', 'Trademark');
    private $NumberTypes = array('application', 'publication');

    private $results = [];
    private $success;
    private $dataSource;

    /**
     * Sets the IP type for the Search, e.g. Patent or Trademark
     * @param mixed $IPType
     *
     * @return $this
     */
    public function setIPType($IPType) {
        if(!in_array($IPType,$this->IPTypes)){
            throw new InvalidArgumentException("Invalid IP Type was specified, should be one of ".json_encode($this->IPTypes));
        }
        $this->IPType = $IPType;
        return $this;
    }

    /**
     * Sets the Number type for the search e.g. application or publication
     * @param mixed $NumberType
     *
     * @return $this
     */
    public function setNumberType($NumberType) {
        if(!in_array($NumberType,$this->NumberTypes)){
            throw new InvalidArgumentException("Invalid Number Type was specified, should be one of ".json_encode($this->NumberTypes));
        }
        $this->NumberType = $NumberType;
        return $this;
    }

    /**
     * Sets the number to be used for the search e.g. EP1234567
     * @param mixed $number
     *
     * @return $this
     */
    public function setNumber($number) {
        $this->number = $number;
        return $this;
    }

    /**
     * Search the Official Offices for the information regarding the number you entered
     * return void
     */
    public function search(){

        //Write the class name into a variable
        $searchObj = 'WAL\\IPFO\\Controllers\\'.$this->IPType.'Controller';

        if(!class_exists($searchObj)){
            throw new InvalidArgumentException("Class for search was not found, class was ".$searchObj);
        }

        //Instantiate a new Search class
        /** @var Controller $searchClass */
        $searchClass = new $searchObj();
        $numberType = $this->NumberType;

        //Run the search
        if($searchClass->numberSearch($this->number,$this->NumberType)) {
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
    public function getResultCollection() {
        return $this->results;
    }

} 