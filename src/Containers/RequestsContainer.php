<?php

namespace SNicholson\IPFO\Containers;

use SNicholson\IPFO\EPO\EPORequest;
use SNicholson\IPFO\USPTO\USPTORequest;
use SNicholson\IPFO\Requests\USPTOTrademarkRequest;
use SNicholson\IPFO\WIPO\WIPORequest;

class RequestsContainer
{

    public function newEPORequest()
    {
        return new EPORequest();
    }

    public function newUSPTORequest()
    {
        return new USPTORequest();
    }

    public function newWIPORequest()
    {
        return new WIPORequest();
    }

    public function newUSPTOTrademarkRequest()
    {
        return new USPTOTrademarkRequest();
    }
}
