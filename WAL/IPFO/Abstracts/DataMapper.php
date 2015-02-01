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
    protected $mappedResponse;
    protected $unmappedResponse;

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
        $mappedData = [];
        $this->mapped = true;
        $this->mappedResponse = $mappedData;
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