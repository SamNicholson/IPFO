<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:08
 */

namespace WAL\IPFO\Containers;


use WAL\IPFO\Requests\EPORequest;
use WAL\IPFO\Requests\USPTORequest;
use WAL\IPFO\Requests\WIPORequest;

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