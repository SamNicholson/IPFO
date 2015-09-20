<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 22:07
 */

namespace SNicholson\IPFO\Abstracts;

use SNicholson\IPFO\Result;
use SNicholson\IPFO\Searches\SearchError;

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
     * @return SearchError|Result
     */
    public function getSearchResult()
    {
        return $this->mapData();
    }

    abstract protected function mapData();

    protected function checkDuplicateParty($newMemberSequence, $partyList)
    {
        foreach ($partyList as $existingMember) {
            if ($newMemberSequence == $existingMember['sequence']) {
                return true;
            }
        }
        return false;
    }

} 