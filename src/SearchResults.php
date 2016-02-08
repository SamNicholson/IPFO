<?php

namespace SNicholson\IPFO;

class SearchResults
{
    /**
     * @var IPRight[]
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
     * @param IPRight $response
     *
     */
    public function addResponse(IPRight $response)
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
     * @return IPRight
     */
    public function getResult()
    {
        return $this->responses[0];
    }
}