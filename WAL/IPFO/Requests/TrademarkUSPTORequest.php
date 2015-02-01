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

class USPTORequest extends Request {


    //https://tsdrapi.uspto.gov/ts/cd/casestatus/ sn78787878/content.zip

    function sampleRequest(){

        $client = new Client();
        //$request = $client->createRequest('GET', 'https://tsdrapi.uspto.gov/ts/cd/casestatus/sn12456689/content.zip');
        $request = $client->createRequest('GET', 'https://tsdrapi.uspto.gov/ts/cd/casestatus/sn78787878/info.xml');




        try {
            $response = $client->send($request);

            $body = $response->getBody();

            $bodyContent = '';

            while (!$body->eof()) {
                $bodyContent .= $body->read(1024);
            }

            $zipFile = new ZIPFromString($bodyContent);

            $requestContents = $zipFile->getContents();

            foreach($requestContents As $filename => $content) {
                if(stristr($filename,'.xml')){
                    $this->response = json_decode(json_encode(simplexml_load_string($content)),true);
                }
            }

            if($this->response){
                return $this->mapData();

            }
            else {
                throw new FileHandleException('Unable to parse XML Document into JSON from USPTO');
            }
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
        //return $output;
    }

    function genRequestURI($number,$numberType){

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

    function simpleNumberSearch($number,$numberType){

    }

} 