<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 15/11/2014
 * Time: 15:06
 */

namespace SNicholson\IPFO\Requests;

use GuzzleHttp;
use GuzzleHttp\Client;
use SNicholson\IPFO\Abstracts\Request;
use SNicholson\IPFO\Exceptions\DataMappingException;
use SNicholson\IPFO\Exceptions\FileHandleException;
use SNicholson\IPFO\Helpers\ZIPFromString;
use SNicholson\IPFO\Interfaces\RequestInterface;

class USPTOTrademarkRequest extends Request implements RequestInterface {

    private $baseURI = 'https://tsdrapi.uspto.gov/ts/cd/casestatus/';
    protected $dataMapper;
    protected $source = 'USPTO';

    function simpleNumberSearch($number,$numberType){

        $client = new Client();
        $request = $client->createRequest("GET",$this->genRequestURI($number,$numberType));

        try {
            $response = $client->send($request);

            $body = $response->getBody();

            $bodyContent = '';

            while (!$body->eof()) {
                $bodyContent .= $body->read(1024);
            }

            $responseJSON = json_decode($bodyContent,true);

            $this->dataMapper = $this->dataMapperContainer->newUSPTOTrademarkDataMapper();
            $this->dataMapper->setResponse($responseJSON);
            $output = $this->dataMapper->getMappedResponse();

        }
        catch(GuzzleHttp\Exception\ClientException $e){
            $this->error = $e->getMessage();
            return false;
        }
        catch(FileHandleException $e){
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

    function genRequestURI($number,$numberType){
        if(substr($number,0,2) == 'US'){
            $number = substr($number,2);
        }
        return $this->baseURI.'sn'.$number.'/info.json';
    }


    function mapData(){

        try {
            if(!is_array($this->response)){
                throw new DataMappingException('SearchResponse from USPTO was not in the expected array format');
            }

        }
        catch(DataMappingException $e) {
            return $e->getMessage();
        }

        return array();
    }


} 