<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:08
 */

namespace SNicholson\IPFO\Containers;


use SNicholson\IPFO\Requests\EPORequest;
use SNicholson\IPFO\Requests\USPTORequest;
use SNicholson\IPFO\Requests\USPTOTrademarkRequest;
use SNicholson\IPFO\Requests\WIPORequest;

class RequestsContainer {

    function __construct(){

    }

    function newEPORequest(){
        return new EPORequest();
    }

    function newUSPTORequest(){
        return new USPTORequest();
    }

    function newWIPORequest(){
        return new WIPORequest();
    }


    function newUSPTOTrademarkRequest(){
        return new USPTOTrademarkRequest();
    }
} 