<?php

namespace SNicholson\IPFO\Containers;

use SNicholson\IPFO\Requests\EPORequest;
use SNicholson\IPFO\Requests\USPTORequest;
use SNicholson\IPFO\Requests\USPTOTrademarkRequest;
use SNicholson\IPFO\Requests\WIPORequest;

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
