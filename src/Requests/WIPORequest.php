<?php

namespace WAL\IPFO\Requests;

use WAL\IPFO\Abstracts\Request;
use WAL\IPFO\Exceptions\DataMappingException;
use GuzzleHttp;
use SoapClient;

class WIPORequest extends Request {

    private $baseURI = 'http://www.wipo.int/patentscope-webservice/servicesPatentScope?wsdl';
    private $username = 'workanyware';
    private $password = 'R_tRebru2';
    protected $dataMapper;
    protected $source = 'WIPO';

    public function simpleNumberSearch($number,$numberType){

        if($number = $this->checkNumberFormat($number,$numberType)){


            $client = new SoapClient($this->baseURI, array(
                    "trace"=>1,
                    "exceptions"=>0,
                    'login' => $this->username,
                    'password' => $this->password
                )
            );

            $this->response = $client->getIASR(['iaNumber' => $number]);

            $this->dataMapper = $this->dataMapperContainer->newWIPODataMapper();
            $output = $this->dataMapper->setResponse($this->response)->getMappedResponse();

        }
        else {
            $this->error =  "Number was not matched, not a WIPO compliant number";
            return false;
        }
        $this->mapResponseToObject($output);
        return true;
    }

    protected function checkNumberFormat($number,$numberType){

        $re = "/[PCT]{0,3}[\\/]{0,1}(?<country>[a-zA-Z]*)(?<year>[0-9]{0,4})[\\/]{0,1}(?<number>[0-9]*)/";

        preg_match_all($re, $number, $matches);

        if(!empty($matches['country']) && !empty($matches['year']) && !empty($matches['number'])) {
            $number = $matches['country'][0].$matches['year'][0].$matches['number'][0];
            return $number;
        }
        return false;
    }
}