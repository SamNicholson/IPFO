<?php

namespace SNicholson\IPFO\Parsers\SmartParsers;

use WorkAnyWare\IPFO\Parties\Applicant;

class ApplicantParser extends PartyMemberParser
{
    protected $memberTypeName = ['applicant', 'ep-applicant'];
    protected $memberTypeNames = ['applicants'];
    protected $memberClass = Applicant::class;
}