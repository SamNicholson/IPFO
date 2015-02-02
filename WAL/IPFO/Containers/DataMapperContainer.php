<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:08
 */

namespace WAL\IPFO\Containers;


use WAL\IPFO\DataMappers\EPODataMapper;
use WAL\IPFO\DataMappers\USPTODataMapper;

class DataMapperContainer {

    public function __construct(){

    }

    /** @return EPODataMapper */
    public function newEPODataMapper(){
        return new EPODataMapper();
    }
    /** @return USPTODataMapper */
    public function newUSPTODataMapper(){
        return new USPTODataMapper();
    }

} 