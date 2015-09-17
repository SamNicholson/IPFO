<?php

namespace SNicholson\IPFO\Containers;

use SNicholson\IPFO\DataMappers\WIPODataMapper;
use SNicholson\IPFO\DataMappers\EPODataMapper;
use SNicholson\IPFO\DataMappers\USPTODataMapper;
use SNicholson\IPFO\DataMappers\USPTOTrademarkDataMapper;

class DataMapperContainer
{
    /** @return EPODataMapper */
    public function newEPODataMapper()
    {
        return new EPODataMapper();
    }

    /** @return USPTODataMapper */
    public function newUSPTODataMapper()
    {
        return new USPTODataMapper();
    }

    /** @return USPTOTrademarkDataMapper */
    public function newUSPTOTrademarkDataMapper()
    {
        return new USPTOTrademarkDataMapper();
    }

    /** @return USPTOTrademarkDataMapper */
    public function newWIPODataMapper()
    {
        return new WIPODataMapper();
    }
}
