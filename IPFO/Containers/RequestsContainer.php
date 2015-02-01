<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:08
 */

namespace IPFO\Containers;


use IPFO\Requests\EPO\EPORequest;
use IPFO\Requests\USPTORequest;
use IPFO\Requests\WIPORequest;

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

} 