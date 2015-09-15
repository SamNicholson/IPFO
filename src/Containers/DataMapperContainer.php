<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:08
 */

namespace SNicholson\IPFO\Containers;


use SNicholson\IPFO\DataMappers\WIPODataMapper;
use SNicholson\IPFO\DataMappers\EPODataMapper;
use SNicholson\IPFO\DataMappers\USPTODataMapper;
use SNicholson\IPFO\DataMappers\USPTOTrademarkDataMapper;

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
    /** @return USPTOTrademarkDataMapper */
    public function newUSPTOTrademarkDataMapper(){
        return new USPTOTrademarkDataMapper();
    }
    /** @return USPTOTrademarkDataMapper */
    public function newWIPODataMapper(){
        return new WIPODataMapper();
    }

} 