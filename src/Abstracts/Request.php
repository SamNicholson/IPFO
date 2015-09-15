<?php

namespace SNicholson\IPFO\Abstracts;

use SNicholson\IPFO\Containers\DataMapperContainer;
use SNicholson\IPFO\Interfaces\RequestInterface;
use SNicholson\IPFO\Result;
use SNicholson\IPFO\ResultCollection;
use SNicholson\IPFO\ValueObjects\SearchSource;

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
        $this->responseObject      = new ResultCollection();
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

    protected function mapResponseToObject($response)
    {

        $obj = new Result();

        if (isset($response['application-reference']['country'])) {
            $obj->setApplicationCountry($response['application-reference']['country']);
        }
        if (isset($response['application-reference']['date'])) {
            $obj->setApplicationDate($response['application-reference']['date']);
        }
        if (isset($response['application-reference']['number'])) {
            $obj->setApplicationNumber($response['application-reference']['number']);
        }
        if (isset($response['publication-reference']['country'])) {
            $obj->setPublicationCountry($response['publication-reference']['country']);
        }
        if (isset($response['publication-reference']['date'])) {
            $obj->setPublicationDate($response['publication-reference']['date']);
        }
        if (isset($response['publication-reference']['number'])) {
            $obj->setPublicationNumber($response['publication-reference']['number']);
        }
        if (isset($response['grant-reference']['country'])) {
            $obj->setGrantCountry($response['grant-reference']['country']);
        }
        if (isset($response['grant-reference']['date'])) {
            $obj->setGrantDate($response['grant-reference']['date']);
        }
        if (isset($response['grant-reference']['number'])) {
            $obj->setGrantNumber($response['grant-reference']['number']);
        }
        if (isset($response['titles']['english'])) {
            $obj->setEnglishTitle($response['titles']['english']);
        }
        if (isset($response['titles']['french'])) {
            $obj->setFrenchTitle($response['titles']['french']);
        }
        if (isset($response['titles']['german'])) {
            $obj->setGermanTitle($response['titles']['german']);
        }
        if (isset($response['parties']['applicants'])) {
            $obj->setApplicants($response['parties']['applicants']);
        }
        if (isset($response['parties']['inventors'])) {
            $obj->setInventors($response['parties']['inventors']);
        }
        $obj->setSource($this->source);

        $this->responseObject->addResponse($obj);

    }

    /**
     * @return SearchSource
     */
    public function getDataSource()
    {
        return SearchSource::USPTO();
    }

    public function getResponse()
    {
        return $this->responseObject;
    }
}
