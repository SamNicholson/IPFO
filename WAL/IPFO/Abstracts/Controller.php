<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:07
 */

namespace WAL\IPFO\Abstracts;


use WAL\IPFO\Containers\RequestsContainer;

abstract class Controller {

    protected $requestsContainer;
    protected $requestNumber;

    protected $error;

    function __construct(){
        $this->requestsContainer = new RequestsContainer();
    }

    public function numberSearch($number,$numberType){
        //Set up some variables
        $this->requestNumber = $number;
        $success = false;
        $searchOutput = false;

        //Find the official office we should search from the number format
        $searchObj = $this->findOfficeFromNumber($this->requestNumber);

        if($searchObj) {
            /** @var $searchObj Request*/
            $searchOutput = $searchObj->simpleNumberSearch($this->requestNumber, $numberType);

            if (is_array($searchOutput)) {
                $success = true;
            }
            else {
                $this->error = $searchOutput;
            }
        }
        else {
            $this->error = "We are unable to search on the format of the number specified, we did not match the format";
        }

        if($success){
            return $searchOutput;
        }
        else {
            return false;
        }
    }

    protected function findOfficeFromNumber($number){
        return false;
    }


} 