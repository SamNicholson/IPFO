<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:07
 */

namespace WAL\IPFO\Abstracts;

/**
 * @property mixed mapped
 * @property mixed mappedResponse
 */
abstract class DataMapper {

    protected $mapped;
    protected $unmappedResponse;

    protected $mappedResponse = array(
    );

    protected $results;

    public function setResponse($response){
        $this->unmappedResponse = $response;
        return $this;
    }

    public function getMappedResponse(){
        if(!$this->mapped){
            $this->mapData();
        }
        return $this->mappedResponse;
    }

    protected function mapData() {

        $this->mappedResponse['publication-reference'] = $this->getPublication();
        $this->mappedResponse['application-reference'] = $this->getApplication();
        $this->mappedResponse['grant-reference'] = $this->getGrant();
        $this->mappedResponse['priority-claims'] = $this->getPriorities();
        $this->mappedResponse['parties'] = $this->getParties();
        $this->mappedResponse['titles'] = $this->getTitles();
        $this->mappedResponse['citations'] = $this->getCitations();

        $this->mapped = true;
    }

    protected function getPublication(){
        return array();
    }

    protected function getApplication(){
        return array();
    }

    protected function getGrant(){
        return array();
    }

    protected function getPriorities(){
        return array();
    }

    protected function getParties(){
        return array();
    }

    protected function getTitles(){
        return array();
    }

    protected function getCitations(){
        return array();
    }

    protected function checkDuplicatePart($newMemberSequence,$partyList){
        foreach($partyList AS $existingMember){
            if($newMemberSequence == $existingMember['sequence']){
                return true;
            }
        }
        return false;
    }

} 