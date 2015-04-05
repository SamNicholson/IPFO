<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 05/04/2015
 * Time: 16:12
 */

namespace WAL\IPFO;



class SearchResponseCollection {
    /**
     * @var
     */
    private $responses;
    private $success;

    /**
     * @return mixed
     */
    public function getSuccess() {
        return $this->success;
    }

    /**
     * @param mixed $success
     */
    public function setSuccess($success) {
        $this->success = $success;
    }

    /**
     * @param SearchResponse $response
     *
     */
    public function addResponse(SearchResponse $response)
    {
        $this->responses[] = $response;
    }

    public function getResponses(){
        return $this->responses;
    }

    public function getResult(){
        return $this->responses[0];
    }
}