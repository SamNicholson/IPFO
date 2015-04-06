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

class TrademarkController extends Controller implements ControllerInterface {

    protected function findOfficeFromNumber($number){
        //TODO massively improve this, to include fantastic validation etc.
        if(substr($number,0,2) == 'US' && $this->requestNumberType == 'application'){
            return $this->requestsContainer->newUSPTOTrademarkRequest();
        }
        return $this->requestsContainer->newUSPTOTrademarkRequest();
    }

}