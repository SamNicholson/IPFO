<?php

namespace SNicholson\IPFO\Parsers\SmartParsers;

use SNicholson\IPFO\Parties\Applicant;

class ApplicantParser extends PartyMemberParser
{
    protected $memberTypeName = ['applicant', 'ep-applicant'];
    protected $memberTypeNames = ['applicants'];
    protected $memberClass = Applicant::class;
}