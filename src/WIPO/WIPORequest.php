<?php

namespace SNicholson\IPFO\WIPO;

use SNicholson\IPFO\Abstracts\Request;
use SNicholson\IPFO\Exceptions\DataMappingException;
use GuzzleHttp;
use SNicholson\IPFO\Searches\SearchError;
use SNicholson\IPFO\ValueObjects\SearchSource;
use SoapClient;

class WIPORequest extends Request
{

    private $baseURI = 'http://www.wipo.int/patentscope-webservice/servicesPatentScope?wsdl';
    private $username = 'workanyware';
    private $password = 'eZ3Bay8';
    protected $dataMapper;
    protected $source = 'WIPO';

    public function simpleNumberSearch($number, $numberType)
    {
        try {
            if ($number = $this->checkNumberFormat($number, $numberType)) {
                $client = new SoapClient(
                    $this->baseURI,
                    [
                        "trace"      => 1,
                        "exceptions" => 1,
                        'login'      => $this->username,
                        'password'   => $this->password
                    ]
                );

                $this->response = $client->getIASR(['iaNumber' => $number]);

                $this->dataMapper = $this->dataMapperContainer->newWIPODataMapper();
                $output           = $this->dataMapper->setResponse($this->response)->getSearchResult();

            } else {
                $this->error = SearchError::fromString("Number was not matched, not a WIPO compliant number");
                return false;
            }
        } catch (\Exception $e) {
            $this->error = SearchError::fromString('Error from WIPO service: ' . $e->getMessage());
            return false;
        }
        if ($output instanceof SearchError) {
            $this->error = $output;
            return false;
        }
        $output->setSource(SearchSource::WIPO());
        return $output;
    }

    protected function checkNumberFormat($number, $numberType)
    {

        $re = "/[PCT]{0,3}[\\/]{0,1}(?<country>[a-zA-Z]*)(?<year>[0-9]{0,4})[\\/]{0,1}(?<number>[0-9]*)/";

        preg_match_all($re, $number, $matches);

        if (!empty($matches['country']) && !empty($matches['year']) && !empty($matches['number'])) {
            $number = $matches['country'][0] . $matches['year'][0] . $matches['number'][0];

            return $number;
        }
        return false;
    }

    protected function genRequestURI($number, $numberType)
    {
    }

    /**
     * @return SearchSource
     */
    public function getDataSource()
    {
        return SearchSource::WIPO();
    }
}