<?php

namespace SNicholson\IPFO\Parsers\SmartParsers;

use SNicholson\IPFO\Parsers\RecursiveFieldSearch;
use SNicholson\IPFO\Parties\Applicant;
use SNicholson\IPFO\Parties\Party;

class ApplicantParser
{
    public $party;

    public static function parse(array $potentialApplicantData)
    {
        $parser = new ApplicantParser($potentialApplicantData);
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

    private function __construct(array $potentialApplicantData)
    {
        $this->party = new Party();
    }

    public function parseSingleApplicant($possibleApplicantArray)
    {
        if($applicantContents = RecursiveFieldSearch::searchByName('applicant', $possibleApplicantArray)) {
            $applicant = new Applicant();
            $applicant->setName(
                $this->combineFields(
                    RecursiveFieldSearch::searchByName('last-name', $possibleApplicantArray),
                    ', ',
                    RecursiveFieldSearch::searchByName('first-name', $possibleApplicantArray)
                )
            );
            $applicant->setPhone(
                RecursiveFieldSearch::searchByName('phone', $possibleApplicantArray)
            );
            $applicant->setEmail(
                RecursiveFieldSearch::searchByName('phone', $possibleApplicantArray)
            );
            $applicant->setFax(
                RecursiveFieldSearch::searchByName('fax', $possibleApplicantArray)
            );
            return $applicant;
        }
        return false;
    }

    private function getMultipleApplicants($applicantsArray)
    {
        $party = new Party();
        if($applicants = RecursiveFieldSearch::searchByName('applicants', $applicantsArray)) {
            var_dump($applicants);
            foreach ($applicants as $applicantContents) {
                $applicant = $this->parseSingleApplicant($applicantContents);
                $party->addMember($applicant);
            }
        }
        return $party;
    }

    private function combineFields(...$fields)
    {
        $response = '';
        foreach ($fields as $field) {
            if (is_string($field)) {
                $response .= $field;
            }
        }
        return $response;
    }
}