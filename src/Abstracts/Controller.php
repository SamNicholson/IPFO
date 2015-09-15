<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:07
 */

namespace SNicholson\IPFO\Abstracts;


use SNicholson\IPFO\Containers\RequestsContainer;

abstract class Controller {

    protected $requestsContainer;
    protected $requestNumber;
    protected $requestNumberType;
    /** @var $searchObj Request*/
    protected $searchObj;
    
    
    protected $error;

    function __construct(){
        $this->requestsContainer = new RequestsContainer();
    }

    /**
     * @return mixed
     */
    public function getError() {
        return $this->error;
    }

    public function numberSearch($number,$numberType){
        //Set up some variables
        $this->requestNumber = $number;
        $this->requestNumberType = $numberType;

        //Find the official office we should search from the number format
        $this->searchObj = $this->findOfficeFromNumber($this->requestNumber);

        if($this->searchObj) {

            if($this->searchObj->simpleNumberSearch($this->requestNumber, $numberType)) {
                return true;
            }
            else {
                $this->error = $this->searchObj->getError();
                return false;
            }
        }
        else {
            $this->error = "We are unable to search on the format of the number specified, we did not match the format";
            return false;
        }

    }

    public function getSearchSource(){
        return $this->searchObj->getDataSource();
    }
    
    public function getResultCollection(){
        return $this->searchObj->getResponse();
    }

    protected function findOfficeFromNumber($number){
        return false;
    }


} 