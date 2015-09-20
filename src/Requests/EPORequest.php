<?php

namespace SNicholson\IPFO\Requests;

use SNicholson\IPFO\Abstracts\Request;
use GuzzleHttp;
use SNicholson\IPFO\ValueObjects\SearchSource;

class EPORequest extends Request
{

    protected $source = 'EPO';
    private $baseURI = 'http://ops.epo.org/3.1/rest-services/published-data/';
    protected $dataMapper;

    public $kindCodes = [
        'A1' => 'European patent application published with European search report',
        'A2' =>
            'European patent application published without European search report' .
            ' (search report not available at publication date)',
        'A3' => 'Separate publication of European search report',
        'A4' => 'Supplementary search report',
        'A8' => 'Corrected title page of A document, ie. A1 or A2 document',
        'A9' => 'Complete reprint of A document, ie. A1, A2 or A3 document',
    ];

    public function simpleNumberSearch($number, $numberType)
    {

        $requestURI = $this->genRequestURI($number, $numberType);

        $client  = new GuzzleHttp\Client();
        $request = $client->createRequest('GET', $requestURI, ['headers' => ['Accept' => 'application/json']]);

        try {
            $response         = $client->send($request);
            $this->response   = $response->json();
            $this->dataMapper = $this->dataMapperContainer->newEPODataMapper();
            $output           = $this->dataMapper->setResponse($this->response)->getSearchResult();
        } catch (GuzzleHttp\Exception\ClientException $e) {
            $this->error = $e->getMessage();
            return false;
        }
        if (is_string($output)) {
            $this->error = $output;
            return false;
        }
        return $output;
    }

    protected function genRequestURI($number, $numberType)
    {
        switch ($numberType) {
            case 'application':
                return $this->baseURI . 'application/epodoc/' . $number . '/biblio';
                break;
            case 'publication':
                return $this->baseURI . 'publication/epodoc/' . $number . '/biblio';
                break;
        }
        return false;
    }

    public function getDataSource()
    {
        return SearchSource::EPO();
    }
}
