<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 15/11/2014
 * Time: 15:06
 */

namespace WAL\IPFO\Requests;

use GuzzleHttp;
use GuzzleHttp\Client;
use WAL\IPFO\Abstracts\Request;
use WAL\IPFO\Exceptions\DataMappingException;
use WAL\IPFO\Exceptions\FileHandleException;
use WAL\IPFO\Helpers\ZIPFromString;
use WAL\IPFO\Interfaces\RequestInterface;

class USPTOTrademarkRequest extends Request implements RequestInterface {

    private $baseURI = 'https://tsdrapi.uspto.gov/ts/cd/casestatus/';
    protected $dataMapper;

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
            switch($e->getCode()){
                case '504':
                    return 'USPTO Service Timed Out';
                    break;
                case '500':
                    return 'USPTO Service Internal Server Error';
                    break;
                case '404':
                    return 'Unable to locate Patent in the USPTO Database';
                    break;
                default:
                    return 'USPTO Service Unknown Error';
                    break;
            }
        }
        catch(FileHandleException $e){
            return $e->getMessage();
        }
        return $output;
    }

    function genRequestURI($number,$numberType){
        if(substr($number,0,2) == 'US'){
            $number = substr($number,2);
        }
        return $this->baseURI.'sn'.$number.'/info.json';
    }


    function mapData(){

        debug($this->response);
        try {
            if(!is_array($this->response)){
                throw new DataMappingException('Response from USPTO was not in the expected array format');
            }

        }
        catch(DataMappingException $e) {
            return $e->getMessage();
        }

        return array();
    }


} 