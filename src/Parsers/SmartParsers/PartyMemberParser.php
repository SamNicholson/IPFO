<?php

namespace SNicholson\IPFO\Parsers\SmartParsers;

use SNicholson\IPFO\Helpers\IPFOXML;
use SNicholson\IPFO\Parsers\RecursiveFieldSearch;
use WorkAnyWare\IPFO\Parties\Agent;
use WorkAnyWare\IPFO\Parties\Applicant;
use WorkAnyWare\IPFO\Parties\Party;
use WorkAnyWare\IPFO\Parties\PartyMember;
use WorkAnyWare\IPFO\Parties\PartyMemberInterface;

abstract class PartyMemberParser
{

    public $party;

    protected $memberTypeName;
    protected $memberTypeNames;
    protected $memberClass;

    protected $reference;

    public static function parse(IPFOXML $potentialApplicantData)
    {
        $thisClass = static::class;
        /** @var PartyMemberParser $parser */
        $parser = new $thisClass($potentialApplicantData);
        $canParseMultipleApplicants = $parser->getMultipleApplicants($potentialApplicantData);
        if ($canParseMultipleApplicants) {
            return $canParseMultipleApplicants;
        }
        $canGetSingleApplicant = $parser->parseSingleApplicant($potentialApplicantData);
        if ($canGetSingleApplicant) {
            $parser->party->addMember($canGetSingleApplicant);
            return $parser->party;
        }
        return $parser->party;
    }

    protected function __construct(IPFOXML $potentialApplicantData)
    {
        $this->party = new Party();
        $this->reference = RecursiveFieldSearch::searchByNameArray(
            ['file-reference-id', 'reference'],
            $potentialApplicantData
        );
    }

    protected function getMultipleApplicants($membersArray)
    {
        $party = new Party();
        if($members = RecursiveFieldSearch::getAllFieldsMatchingNameByArray($this->memberTypeName, $membersArray)) {
            foreach ($members as $memberContents) {
                $member = $this->parseSingleApplicant($memberContents);
                if ($member InstanceOf $this->memberClass) {
                    $party->addMember($member);
                }
            }
        }
        return $party;
    }

    public function parseSingleApplicant($possibleApplicantArray)
    {
        if($memberContents = RecursiveFieldSearch::searchByNameArray($this->memberTypeName, $possibleApplicantArray)) {
            $possibleApplicantArray = $memberContents;
        }
        /** @var $member PartyMember */
        $member = new $this->memberClass();
        $this->parseApplicantName($member, $possibleApplicantArray);
        $member->setPhone(
            RecursiveFieldSearch::searchByName('phone', $possibleApplicantArray)
        );
        $member->setEmail(
            RecursiveFieldSearch::searchByName('email', $possibleApplicantArray)
        );
        $member->setFax(
            RecursiveFieldSearch::searchByName('fax', $possibleApplicantArray)
        );
        $member->setNationality($this->parseNationality($possibleApplicantArray));
        $member->setDomicile(
            RecursiveFieldSearch::searchByName('domicile', $possibleApplicantArray)
        );
        $member->getAddress()->setAddress(
            $this->combineFields(
                [RecursiveFieldSearch::searchByName('address-1', $possibleApplicantArray), ", "],
                [RecursiveFieldSearch::searchByName('street', $possibleApplicantArray), ", "],
                RecursiveFieldSearch::searchByName('city', $possibleApplicantArray)
            )
        );
        $member->getAddress()->setCountry(
            RecursiveFieldSearch::searchByName('country', $possibleApplicantArray)
        );
        $member->getAddress()->setPostCode(
            RecursiveFieldSearch::searchByName('postcode', $possibleApplicantArray)
        );
        if ($this->memberClass == Agent::class) {
            $member->setReference($this->reference);
        }
        return $member;
    }

    protected function parseNationality($array)
    {
        $nationality = RecursiveFieldSearch::searchByName('nationality', $array);
        if (is_string($nationality)) {
            return $nationality;
        }
        if ($countryInNationality = RecursiveFieldSearch::searchByName('country', $nationality)) {
            return $countryInNationality;
        }
        return null;
    }

    private function parseApplicantName(PartyMemberInterface &$member, $possibleApplicantArray)
    {
        $member->setName(
            $this->combineFields(
                RecursiveFieldSearch::searchByNameArray(['last-name', 'name'], $possibleApplicantArray),
                [', ', RecursiveFieldSearch::searchByName('first-name', $possibleApplicantArray)]
            )
        );
    }

    protected function combineFields(...$fields)
    {
        $response = '';
        foreach ($fields as $field) {
            if (is_string($field)) {
                $response .= $field;
            }
            if (is_array($field)) {
                if (!empty($field[0]) && !empty($field[1])) {
                    $response .= $field[0] . $field[1];
                }
            }
            if (is_object($field)) {
                return (string) $field;
            }
        }
        if ($response) {
            return $response;
        }
        return null;
    }
}
