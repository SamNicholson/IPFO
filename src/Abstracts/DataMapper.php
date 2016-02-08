<?php

namespace SNicholson\IPFO\Abstracts;

use SNicholson\IPFO\IPRight;
use SNicholson\IPFO\IPRightInterface;
use SNicholson\IPFO\Searches\SearchError;
use SNicholson\IPFO\Parties\Party;

/**
 * @property mixed mapped
 * @property mixed mappedResponse
 */
abstract class DataMapper
{

    protected $mapped;
    protected $unmappedResponse;

    protected $mappedResponse = array();

    protected $results;

    public function setResponse($response)
    {
        $this->unmappedResponse = $response;
        return $this;
    }

    /**
     * @return SearchError|IPRightInterface
     */
    public function getSearchResult()
    {
        return $this->mapData();
    }

    protected function mapData()
    {
        return SearchError::fromString('Map Data Method should be overwritten when extending');
    }

    protected function checkDuplicateParty($newMemberSequence, Party $partyList)
    {
        foreach ($partyList as $existingMember) {
            if ($newMemberSequence == $existingMember['sequence']) {
                return true;
            }
        }
        return false;
    }
}
