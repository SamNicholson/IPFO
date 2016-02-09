<?php

namespace SNicholson\IPFO;

use WorkAnyWare\IPFO\IPRightInterface;

class SearchResults
{
    /**
     * @var IPRightInterface[]
     */
    private $responses = [];
    private $success;
    private $dataSource;

    /**
     * @return mixed
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param mixed $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @param IPRightInterface $response
     *
     */
    public function addResponse(IPRightInterface $response)
    {
        $this->responses[] = $response;
    }

    public function getResponses()
    {
        return $this->responses;
    }

    public function toArray()
    {
        $responses = [];
        foreach ($this->responses as $response) {
            $responses[] = $response->toArray();
        }
        return $responses;
    }

    /**
     * @return IPRightInterface
     */
    public function getResult()
    {
        return isset($this->responses[0]) ? $this->responses[0] : null;
    }
}