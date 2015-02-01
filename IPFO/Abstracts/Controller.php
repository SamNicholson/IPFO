<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:07
 */

namespace IPFO\Abstracts;


use IPFO\Containers\RequestsContainer;

abstract class Controller {

    protected $requestsContainer;

    function __construct(){
        $this->requestsContainer = new RequestsContainer();
    }

} 