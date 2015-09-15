<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:05
 */

namespace SNicholson\IPFO\Controllers;

use SNicholson\IPFO\Abstracts\Controller;
use SNicholson\IPFO\Interfaces\ControllerInterface;
use SNicholson\IPFO\Requests\EPORequest;
use SNicholson\IPFO\Requests\USPTORequest;
use SNicholson\IPFO\Requests\WIPORequest;


class PatentController extends Controller implements ControllerInterface {

    protected $requestNumber = '';
    protected $error;

    protected function findOfficeFromNumber($number){
        if(substr($number,0,2) == 'EP'){
            return $this->requestsContainer->newEPORequest();
        }
        else if(substr($number,0,2) == 'US'){
            return $this->requestsContainer->newUSPTORequest();
        }
        else {
            return $this->requestsContainer->newWIPORequest();
        }
    }

} 