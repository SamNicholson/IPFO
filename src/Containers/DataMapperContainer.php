<?php

namespace SNicholson\IPFO\Containers;

use SNicholson\IPFO\Searches\WIPO\DataMappers\WIPODataMapper;
use SNicholson\IPFO\Searches\EPO\EPODataMapper;
use SNicholson\IPFO\Searches\USPTO\USPTODataMapper;
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

    /** @return WIPODataMapper */
    public function newWIPODataMapper()
    {
        return new WIPODataMapper();
    }
}
