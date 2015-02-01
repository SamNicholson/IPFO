<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:05
 */

namespace IPFO\Controllers;

use IPFO\Abstracts\Controller;
use IPFO\Helpers\Response;
use IPFO\Requests\EPO\EPORequest;
use IPFO\Requests\USPTORequest;
use IPFO\Requests\WIPORequest;


class PatentController extends Controller {

    private $requestNumber = '';

    function application($params){

        $this->requestNumber = $params['number'];

        $searchObj = $this->findPatentSearchOfficeFromNumber($this->requestNumber);

        $success = false;
        $errorString = '';

        $output = $searchObj->simpleNumberSearch($this->requestNumber,'publication');

        if(is_array($output)){
            $success = true;
        }
        else {
            $errorString = $output;
        }

        Response::show($output,$success,$errorString);
    }

    function publication($params){

        $this->requestNumber = $params['number'];

        $searchObj = $this->findPatentSearchOfficeFromNumber($this->requestNumber);

        $success = false;
        $errorString = '';

        $output = $searchObj->simpleNumberSearch($this->requestNumber,'publication');

        if(is_array($output)){
            $success = true;
        }
        else {
            $errorString = $output;
        }

        Response::show($output,$success,$errorString);
    }

    function findPatentSearchOfficeFromNumber($number){
        //TODO massively improve this, to include fantastic validation etc.
        if(substr($number,0,2) == 'EP'){
            return $this->requestsContainer->newEPORequest();
        }
        return false;
    }

} 