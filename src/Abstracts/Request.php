<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 15/11/2014
 * Time: 17:54
 */

namespace WAL\IPFO\Abstracts;

use WAL\IPFO\Containers\DataMapperContainer;
use WAL\IPFO\Interfaces\RequestInterface;

abstract class Request implements RequestInterface {

    public $response = false;

    public $kindCodes;
    public $mappedResponse;
    /** @var DataMapperContainer */
    protected $dataMapperContainer;

    public function __construct(){
        $this->dataMapperContainer = new DataMapperContainer();
    }

    public abstract function simpleNumberSearch($number,$numberType);

    protected function genRequestURI($number,$numberType){
        return null;
    }

}