<?php
/**
 * Created by PhpStorm.
 * User: sam
 * Date: 03/02/15
 * Time: 12:08
 */

namespace SNicholson\IPFO\DataMappers;


use SNicholson\IPFO\Abstracts\DataMapper;
use SNicholson\IPFO\Interfaces\DataMapperInterface;

class USPTOTrademarkDataMapper extends DataMapper implements DataMapperInterface {

    protected function getGrant(){

        $number = '';
        $date = '';
        if(isset( $this->unmappedResponse['trademarks'][0]['status']['usRegistrationDate'])) {
            $date = $this->unmappedResponse['trademarks'][0]['status']['usRegistrationDate'];
        }
        if(isset($this->unmappedResponse['trademarks'][0]['status']['usRegistrationNumber'])){
            $number = $this->unmappedResponse['trademarks'][0]['status']['usRegistrationNumber'];
        }

        return array('date' => $date,'number' => $number, 'country' => 'US');
    }

    protected function getPublication(){

        $number = '';
        $date = '';
        if(isset( $this->unmappedResponse['trademarks'][0]['publication']['datePublished'])) {
            $date = $this->unmappedResponse['trademarks'][0]['publication']['datePublished'];
        }
        if(isset($this->unmappedResponse['trademarks'][0]['publication']['serialNumber'])){
            $number = $this->unmappedResponse['trademarks'][0]['publication']['serialNumber'];
        }

        return array('date' => $date,'number' => $number, 'country' => 'US');
    }

    protected function getApplication(){
        //The application number appears to be the same as the publication (USPTO Serial Number)
        return $this->getPublication();
    }

    protected function getParties(){

        $parties = ['applicants' => [], 'inventors' => []];
        //Iterate through all types of applicants and try to find duplicates
        foreach($this->unmappedResponse['trademarks'][0]['parties']['ownerGroups'] AS $applicantGroup){
            foreach($applicantGroup AS $applicant) {
                if (!$this->checkDuplicateParty($applicant['entityNum'], $parties['applicants'])) {
                    $parties['applicants'][] = [
                        'name'     => $applicant['name'],
                        'sequence' => $applicant['entityNum']
                    ];
                }
            }
        }

        return $parties;

    }

    protected function getTitles(){

        $titles = [
            'english' => $this->unmappedResponse['trademarks'][0]['status']['markElement'],
            'french'  => '',
            'german'  => '',
        ];
        return $titles;
    }

} 