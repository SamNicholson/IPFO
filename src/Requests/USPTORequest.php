<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 15/11/2014
 * Time: 15:06
 */

namespace WAL\IPFO\Requests;

use GuzzleHttp;
use WAL\IPFO\Abstracts\Request;

class USPTORequest extends Request {

    private $baseURI = 'http://patft.uspto.gov/';
    protected $dataMapper;
    public $response;
    protected $source = 'USPTO';

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

        //Strip the "US" off of the start of the number if present, the US doesn't use this bit
        if(substr($number,0,2) == 'US'){
            $number = substr($number,2);
        }

        $requestURI = $this->genRequestURI($number,$numberType);

        try {
            //The USPTO redirects you, we need to emulate that redirect by pulling out the new URL target from the initial response
            $redirectURL = $this->getRedirectURL($requestURI);

            $this->response = $this->getPatentData($redirectURL);

            $this->dataMapper = $this->dataMapperContainer->newUSPTODataMapper();
            $output = $this->dataMapper->setResponse($this->response)->getMappedResponse();
        }
        catch(GuzzleHttp\Exception\ClientException $e){
            $this->error = $e->getMessage();
            return false;
        }
        $this->mapResponseToObject($output);
        return true;
    }

    private function getRedirectURL($requestURI){
        $client = new GuzzleHttp\Client();
        $request = $client->createRequest('GET', $requestURI);

        $response = $client->send($request);
        $redirectResponse = $response->getBody();
        $redirectURL = '';

        //Get the redirect URL
        while (!$redirectResponse->eof()) {
            $redirectURL .= $redirectResponse->read(1024);
        }

        //Preg match the URL out of the response
        $re = "/CONTENT=\"1;URL=(.*)\"/";
        preg_match($re, $redirectURL, $matches);
        //TODO perhaps validate this?
        return $matches[1];
    }

    private function getPatentData($requestURI){
        $client = new GuzzleHttp\Client();
        $request = $client->createRequest('GET', $this->baseURI.$requestURI);

        $response = $client->send($request);
        $response = $response->getBody();
        $textResponse = '';
        //Get the redirect URL
        while (!$response->eof()) {
            $textResponse .= $response->read(1024);
        }

        return $textResponse;
    }

    protected function genRequestURI($number,$numberType){
        switch($numberType){
            case 'publ ication';
                return $this->baseURI.'netacgi/nph-Parser?patentnumber='.$number.'';
                break;
        }
        return false;
    }
}