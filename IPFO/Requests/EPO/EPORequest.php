<?php

namespace IPFO\Requests\EPO;

use IPFO\Abstracts\Request;
use IPFO\Exceptions\DataMappingException;
use GuzzleHttp;

class EPORequest extends Request {

    private $baseURI = 'http://ops.epo.org/3.1/rest-services/published-data/';

    public $kindCodes = array(
        'A1' => 'European patent application published with European search report',
        'A2' => 'European patent application published without European search report (search report not available at publication date)',
        'A3' => 'Separate publication of European search report',
        'A4' => 'Supplementary search report',
        'A8' => 'Corrected title page of A document, ie. A1 or A2 document',
        'A9' => 'Complete reprint of A document, ie. A1, A2 or A3 document',
        //TODO B ones (oh and WIPO ones!! somehow...)
    );

    function findValueFromEPO($possibleArray){
       if(is_array($possibleArray)){
           if(isset($possibleArray['$'])){
               return $possibleArray['$'];
           }
           else {
               return false;
           }
       }
       else {
           return $possibleArray;
       }
    }


    function simpleNumberSearch($number,$numberType){

        $requestURI = $this->genRequestURI($number,$numberType);

        $client = new GuzzleHttp\Client();
        $request = $client->createRequest('GET', $requestURI,['headers' => ['Accept' => 'application/json']]);

        try {
            $response = $client->send($request);
            $this->response = $response->json();
            $output = $this->mapData();
        }
        catch(GuzzleHttp\Exception\ClientException $e){
            switch($e->getCode()){
                case '504':
                    return 'EPO Service Timed Out';
                    break;
                case '500':
                    return 'EPO Service Internal Server Error';
                    break;
                case '404':
                    return 'Unable to locate Patent in the EPO Database';
                    break;
                default:
                    return 'EPO Service Unknown Error';
                    break;
            }
        }
        return $output;
    }

    function genRequestURI($number,$numberType){
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

    function mapData(){

        /*
         * NOT FOUND HANDLER
         * This should handle the situation where the EP Document cannot be found
         *
         */
        if(isset($this->response['ops:world-patent-data']['exchange-documents']['exchange-document']['@status'])){
;            if($this->response['ops:world-patent-data']['exchange-documents']['exchange-document']['@status'] == 'not found'){
                return 'Unable to locate Patent in the EPO Database';
            }
        }

        /*
         * IMPORTANT
         * The EPO structure differs depending on the number of results received (extra array level if multiple results), this fixes that issue and normalises the structure regardless of result count
         */

        if(isset($this->response['ops:world-patent-data'])){
            $this->response = $this->response['ops:world-patent-data'];
        }

        if(!isset($this->response['exchange-documents']['exchange-document'][0])){
            //There is only one result
            if(isset($this->response['exchange-documents']['exchange-document'])){
                $this->response['exchange-documents']['exchange-document'] = array($this->response['exchange-documents']['exchange-document']);
            }
        }

        $mappedResponse = array();

        //Iterate through each of the documented results from the EPO
        foreach($this->response['exchange-documents']['exchange-document'] AS $key => $responseResult){
            try {

                /*
                 * Titles Data
                 */
                if (isset($responseResult['bibliographic-data']['invention-title']) && !isset($mappedResponse['title'])) {
                    $mappedResponse['titles']['french']  = $this->findValueFromEPO($responseResult['bibliographic-data']['invention-title'][0]);
                    $mappedResponse['titles']['english']  = $this->findValueFromEPO($responseResult['bibliographic-data']['invention-title'][1]);
                    $mappedResponse['titles']['german']  = $this->findValueFromEPO($responseResult['bibliographic-data']['invention-title'][2]);
                }

                /*
                 * Citations
                 */
                if (isset($responseResult['bibliographic-data']['references-cited']) && !isset($mappedResponse['citations'])) {
                    $mappedResponse['citations'] = array();
                    foreach($responseResult['bibliographic-data']['references-cited']['citation'] AS $citation){
                        $newCitation = array();
                        //Type of citation
                        if(isset($citation['patcit'])){
                            $newCitation['type'] = 'Patent';
                        }
                        else if(isset($citation['nplcit'])){
                            $newCitation['type'] = 'Non patent literature';
                        }
                        else {
                            $newCitation['type'] = 'Unknown type';
                        }
                        //Cited By
                        if(isset($citation['@attributes']['cited-by'])){
                            $newCitation['cited-by'] = $this->findValueFromEPO($citation['@attributes']['cited-by']);
                        }
                        //Cited in Phase
                        if(isset($citation['@attributes']['cited-phase'])){
                            $newCitation['phase'] = $this->findValueFromEPO($citation['@attributes']['cited-phase']);
                        }
                        //Sequence
                        if(isset($citation['@attributes']['sequence'])){
                            $newCitation['sequence'] = $this->findValueFromEPO($citation['@attributes']['sequence']);
                        }
                        if($newCitation['type'] == 'Patent'){
                            //Number Type
                            if(isset($citation['patcit']['@attributes']['dnum-type'])){
                                $newCitation['number-type'] = $this->findValueFromEPO($citation['patcit']['@attributes']['dnum-type']);
                            }
                            //Number
                            if(isset($citation['patcit']['document-id'][0]['doc-number'])){
                                if(isset($citation['patcit']['document-id'][1]['doc-number'])){
                                    $newCitation['number'] = $this->findValueFromEPO($citation['patcit']['document-id'][1]['doc-number']);
                                    $newCitation['country'] = $this->findValueFromEPO($citation['patcit']['document-id'][1]['country']);
                                }
                                else {
                                    $newCitation['number'] = $this->findValueFromEPO($citation['patcit']['document-id'][0]['doc-number']);
                                    $newCitation['country'] = $this->findValueFromEPO(substr($citation['patcit']['document-id'][0]['doc-number'],0,2));
                                }
                            }
                        }
                        else if($newCitation['type'] == 'Non patent literature'){
                            $newCitation['text'] = $citation['nplcit']['text'];
                        }



                        $mappedResponse['citations'][] = $newCitation;
                    }
                }

                /*
                 * Application Data
                 */
                if (isset($responseResult['bibliographic-data']['application-reference']) && !isset($mappedResponse['application-reference'])) {
                    foreach($responseResult['bibliographic-data']['application-reference']['document-id'][0] AS $fieldName => $field){
                        $mappedResponse['application-reference'][$fieldName]  = $this->findValueFromEPO($field);
                    }

                    //Sometimes there will be no application date in the docdb database!
                    if(isset($responseResult['bibliographic-data']['application-reference']['document-id'][0]['date'])){
                        $mappedResponse['application-reference']['date'] = $this->findValueFromEPO($responseResult['bibliographic-data']['application-reference']['document-id'][0]['date']);
                    }
                    else if(isset($responseResult['bibliographic-data']['application-reference']['document-id'][1]['date'])){
                        $mappedResponse['application-reference']['date'] = $this->findValueFromEPO($responseResult['bibliographic-data']['application-reference']['document-id'][1]['date']);
                    }
                }

                /*
                 * Publication and Grant Data
                 */
                if (isset($responseResult['bibliographic-data']['publication-reference'])) {

                    $kindCode = $this->findValueFromEPO($responseResult['bibliographic-data']['publication-reference']['document-id'][0]['kind']);

                    switch(substr($kindCode,0,1)){
                        case 'A':
                            /*
                             * Publication Data
                             */
                            foreach($responseResult['bibliographic-data']['publication-reference']['document-id'][0] AS $fieldName => $field){
                                $mappedResponse['publication-reference'][$fieldName]  = $this->findValueFromEPO($field);
                            }
                            break;
                        case 'B':
                            /*
                             * Grant Data
                             */
                            foreach($responseResult['bibliographic-data']['publication-reference']['document-id'][0] AS $fieldName => $field){
                                $mappedResponse['grant-reference'][$fieldName]  = $this->findValueFromEPO($field);
                            }
                            break;
                    }
                }

                /*
                 * Priorities Data
                 */
                //TODO fix a bug with these getting picked up, see http://ops.epo.org/3.1/rest-services/published-data/publication/epodoc/EP2285036/biblio for a sample
                if (isset($responseResult['bibliographic-data']['priority-claims']) && !isset($mappedResponse['priority-claims'])) {
                    if(isset($responseResult['bibliographic-data']['priority-claims']['priority-claim'][0])){
                        foreach($responseResult['bibliographic-data']['priority-claims']['priority-claim'] AS $pKey => $priority){
                            $mappedResponse['priority-claims'][] = $priority['document-id'][0];
                        }
                    }
                    else {
                        $mappedResponse['priority-claims'][] = $this->findValueFromEPO($responseResult['bibliographic-data']['priority-claims']['priority-claim']['document-id']);
                    }

                }

                /*
                 * Inventors and Applicants Data
                 */
                if (isset($responseResult['bibliographic-data']['parties']) && !isset($mappedResponse['parties'])) {
                    /*
                     * Applicants
                     * IMPORTANT - The EPO bundles original and EPO database results together, which is confusing, we check to find the unique applicants so that we get a good clean list.
                     */
                    $mappedResponse['parties']['applicants'] = array();
                    foreach($responseResult['bibliographic-data']['parties']['applicants']['applicant'] AS $aKey => $applicant){
                        if(!$this->checkDuplicatePart($applicant['@sequence'],$mappedResponse['parties']['applicants'])) {
                            $mappedResponse['parties']['applicants'][] = array('name' => $this->findValueFromEPO($applicant['applicant-name']['name']), 'sequence' => $this->findValueFromEPO($applicant['@sequence']));
                        }
                    }
                    /*
                     * Inventors
                     * IMPORTANT - The EPO bundles original and EPO database results together, which is confusing, we check to find the unique inventors so that we get a good clean list.
                     */
                    $mappedResponse['parties']['inventors'] = array();
                    foreach($responseResult['bibliographic-data']['parties']['inventors']['inventor'] AS $aKey => $applicant){
                        if(!$this->checkDuplicatePart($applicant['@sequence'],$mappedResponse['parties']['inventors'])) {
                            $mappedResponse['parties']['inventors'][] = array('name' => $this->findValueFromEPO($applicant['inventor-name']['name']), 'sequence' => $this->findValueFromEPO($applicant['@sequence']));
                        }
                    }
                }

                //TODO iterate through all the possible properties and set the ones into the array, thus ensuring the validity of the data, map onto abstract, as its common functionality.

                foreach($this->mappings AS $entry){
                    if(isset($mappedResponse[$entry])){
                        $this->mappedResponse[$entry] = $mappedResponse[$entry];
                    }
                }

                return $this->mappedResponse;
            }
            catch(DataMappingException $e){
                debug($e);
            }
        }
        return false;
    }

    //change request header to application/json
}