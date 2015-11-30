<?php

namespace SNicholson\IPFO\USPTO;

use GuzzleHttp;
use SNicholson\IPFO\Abstracts\Request;
use SNicholson\IPFO\Exceptions\DataMappingException;
use SNicholson\IPFO\Exceptions\InvalidAddressException;
use SNicholson\IPFO\Searches\SearchError;
use SNicholson\IPFO\ValueObjects\SearchSource;

class USPTORequest extends Request
{

    private $baseURI = 'http://patft.uspto.gov/';
    protected $dataMapper;
    public $response;
    protected $source = 'USPTO';

    public $kindCodes = [
        'A1' => 'European patent application published with European search report',
        'A2' => 'European patent application published without European search report'
            . '(search report not available at publication date)',
        'A3' => 'Separate publication of European search report',
        'A4' => 'Supplementary search report',
        'A8' => 'Corrected title page of A document, ie. A1 or A2 document',
        'A9' => 'Complete reprint of A document, ie. A1, A2 or A3 document',
        //TODO B ones (oh and WIPO ones!! somehow...)
    ];

    public function simpleNumberSearch($number, $numberType)
    {

        //Strip the "US" off of the start of the number if present, the US doesn't use this bit
        if (substr($number, 0, 2) == 'US') {
            $number = substr($number, 2);
        }

        $requestURI = $this->genRequestURI($number, $numberType);

        try {
            //The USPTO redirects you, we need to emulate that redirect by pulling out the
            //new URL target from the initial response
            $redirectURL = $this->getRedirectURL($requestURI, $number);

            $this->response = $this->getPatentData($redirectURL);

            $this->dataMapper = $this->dataMapperContainer->newUSPTODataMapper();
            $output           = $this->dataMapper->setResponse($this->response)->getSearchResult();
        } catch (\Exception $e) {
            $this->error = SearchError::fromString($e->getMessage());
            return false;
        }
        if ($output instanceof SearchError) {
            $this->error = $output;
            return false;
        }
        $output->setSource(SearchSource::USPTO());
        return $output;
    }

    private function getRedirectURL($requestURI, $number)
    {
        $client  = new GuzzleHttp\Client();
        $request = $client->createRequest('GET', $requestURI);

        $response         = $client->send($request);
        $redirectResponse = $response->getBody();
        $redirectURL      = '';

        //Get the redirect URL
        while (!$redirectResponse->eof()) {
            $redirectURL .= $redirectResponse->read(1024);
        }

        //Preg match the URL out of the response
        $re = "/CONTENT=\"1;URL=(.*)\"/";
        preg_match($re, $redirectURL, $matches);

        if (empty($matches)) {
            throw new InvalidAddressException("Unable to locate Patent $number in USPTO database");
        }

        return $matches[1];
    }

    private function getPatentData($requestURI)
    {
        $client  = new GuzzleHttp\Client();
        $request = $client->createRequest('GET', $this->baseURI . $requestURI);

        $response     = $client->send($request);
        $response     = $response->getBody();
        $textResponse = '';
        //Get the redirect URL
        while (!$response->eof()) {
            $textResponse .= $response->read(1024);
        }

        return $textResponse;
    }

    protected function genRequestURI($number, $numberType)
    {
        switch ($numberType) {
            case 'publication':
                return $this->baseURI . 'netacgi/nph-Parser?patentnumber=' . $number . '';
                break;
        }
        return false;
    }

    public function getDataSource()
    {
        return SearchSource::USPTO();
    }
}