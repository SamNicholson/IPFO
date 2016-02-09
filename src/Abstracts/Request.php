<?php

namespace SNicholson\IPFO\Abstracts;

use SNicholson\IPFO\Containers\DataMapperContainer;
use SNicholson\IPFO\Interfaces\RequestInterface;
use SNicholson\IPFO\SearchResults;
use WorkAnyWare\IPFO\IPRights\SearchSource;

abstract class Request implements RequestInterface
{

    public $response = false;

    public $kindCodes;
    public $mappedResponse;
    /** @var DataMapperContainer */
    protected $dataMapperContainer;
    protected $source;
    protected $responseObject = [];
    protected $error;

    public function __construct()
    {
        $this->dataMapperContainer = new DataMapperContainer();
        $this->responseObject      = new SearchResults();
    }

    abstract public function simpleNumberSearch($number, $numberType);

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    abstract protected function genRequestURI($number, $numberType);

    /**
     * @return SearchSource
     */
    abstract public function getDataSource();
}
