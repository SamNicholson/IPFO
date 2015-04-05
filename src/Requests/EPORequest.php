<?php

namespace WAL\IPFO\Requests;

use WAL\IPFO\Abstracts\Request;
use WAL\IPFO\Exceptions\DataMappingException;
use GuzzleHttp;

class EPORequest extends Request {

    protected $source = 'EPO';
    private $baseURI = 'http://ops.epo.org/3.1/rest-services/published-data/';
    protected $dataMapper;

    public $kindCodes = array(
        'A1' => 'European patent application published with European search report',
        'A2' => 'European patent application published without European search report (search report not available at publication date)',
        'A3' => 'Separate publication of European search report',
        'A4' => 'Supplementary search report',
        'A8' => 'Corrected title page of A document, ie. A1 or A2 document',
        'A9' => 'Complete reprint of A document, ie. A1, A2 or A3 document',
        //TODO B ones (oh and WIPO ones!! somehow...)
    );

    public function simpleNumberSearch($number,$numberType){

        $requestURI = $this->genRequestURI($number,$numberType);

        $client = new GuzzleHttp\Client();
        $request = $client->createRequest('GET', $requestURI,['headers' => ['Accept' => 'application/json']]);

        try {
            $response = $client->send($request);
            $this->response = $response->json();
            $this->dataMapper = $this->dataMapperContainer->newEPODataMapper();
            $output = $this->dataMapper->setResponse($this->response)->getMappedResponse();
        }
        catch(GuzzleHttp\Exception\ClientException $e){
            $this->error = $e->getMessage();
            return false;
        }
        if(is_string($output)){
            $this->error = $output;
            return false;
        }
        $this->mapResponseToObject($output);
        return true;
    }

    protected function genRequestURI($number,$numberType){
        switch($numberType){
            case 'application':
                return $this->baseURI.'application/epodoc/'.$number.'/biblio';
                break;
            case 'publication';
                return $this->baseURI.'publication/epodoc/'.$number.'/biblio';
                break;
        }
        return false;
    }
}