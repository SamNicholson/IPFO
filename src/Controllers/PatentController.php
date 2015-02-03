<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:05
 */

namespace WAL\IPFO\Controllers;

use WAL\IPFO\Abstracts\Controller;
use WAL\IPFO\Interfaces\ControllerInterface;
use WAL\IPFO\Requests\EPORequest;
use WAL\IPFO\Requests\USPTORequest;
use WAL\IPFO\Requests\WIPORequest;


class PatentController extends Controller implements ControllerInterface {

    protected $requestNumber = '';
    protected $error;

    protected function findOfficeFromNumber($number){
        //TODO massively improve this, to include fantastic validation etc.
        if(substr($number,0,2) == 'EP'){
            return $this->requestsContainer->newEPORequest();
        }
        else if(substr($number,0,2) == 'US'){
            return $this->requestsContainer->newUSPTORequest();
        }
        return false;
    }

} 