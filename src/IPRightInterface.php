<?php

namespace SNicholson\IPFO;

use SNicholson\IPFO\Parties\Agent;
use SNicholson\IPFO\Parties\Party;
use SNicholson\IPFO\ValueObjects\Citation;
use SNicholson\IPFO\ValueObjects\Priority;

interface IPRightInterface
{
    /**
     * @return mixed
     */
    public function getSource();

    /**
     * @param mixed $source
     */
    public function setSource($source);

    /**
     * @return mixed
     */
    public function getApplicationDate();

    /**
     * @param mixed $applicationDate
     */
    public function setApplicationDate($applicationDate);

    /**
     * @return mixed
     */
    public function getApplicationCountry();

    /**
     * @param mixed $applicationCountry
     */
    public function setApplicationCountry($applicationCountry);

    /**
     * @return mixed
     */
    public function getApplicationNumber();

    /**
     * @param mixed $applicationNumber
     */
    public function setApplicationNumber($applicationNumber);

    /**
     * @return mixed
     */
    public function getPublicationDate();

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate);

    /**
     * @return mixed
     */
    public function getPublicationCountry();

    /**
     * @param mixed $publicationCountry
     */
    public function setPublicationCountry($publicationCountry);

    /**
     * @return mixed
     */
    public function getPublicationNumber();

    /**
     * @param mixed $publicationNumber
     */
    public function setPublicationNumber($publicationNumber);

    /**
     * @return mixed
     */
    public function getGrantDate();

    /**
     * @param mixed $grantDate
     */
    public function setGrantDate($grantDate);

    /**
     * @return mixed
     */
    public function getGrantCountry();

    /**
     * @param mixed $grantCountry
     */
    public function setGrantCountry($grantCountry);

    /**
     * @return mixed
     */
    public function getGrantNumber();

    /**
     * @param mixed $grantNumber
     */
    public function setGrantNumber($grantNumber);

    /**
     * @param bool $inArrayFormat
     *
     * @return Party
     */
    public function getApplicants($inArrayFormat = false);

    /**
     * @param mixed $applicants
     */
    public function setApplicants(Party $applicants);

    /**
     * @param bool $inArrayFormat
     *
     * @return Party
     */
    public function getInventors($inArrayFormat = false);

    /**
     * @param mixed $inventors
     */
    public function setInventors(Party $inventors);

    /**
     * @return mixed
     */
    public function getEnglishTitle();

    /**
     * @param mixed $englishTitle
     */
    public function setEnglishTitle($englishTitle);

    /**
     * @return mixed
     */
    public function getFrenchTitle();

    /**
     * @param mixed $frenchTitle
     */
    public function setFrenchTitle($frenchTitle);

    /**
     * @return mixed
     */
    public function getGermanTitle();

    /**
     * @param mixed $germanTitle
     */
    public function setGermanTitle($germanTitle);

    /**
     * @param bool $inArrayFormat
     *
     * @return mixed
     */
    public function getCitations($inArrayFormat = false);

    public function addCitation(Citation $citation);

    /**
     * @param mixed $citations
     */
    public function setCitations(Citation ...$citations);

    /**
     * @param bool $inArrayFormat
     *
     * @return mixed
     */
    public function getPriorities($inArrayFormat = false);

    /**
     * @param mixed $priorities
     */
    public function setPriorities(Priority ...$priorities);

    public function addPriority(Priority $priority);

    public function toArray();

    /**
     * @return mixed
     */
    public function getLanguageOfFiling();

    /**
     * @param mixed $languageOfFiling
     */
    public function setLanguageOfFiling($languageOfFiling);

    /**
     * @param $inArrayFormat
     *
     * @return Agent[]|array
     */
    public function getAgents($inArrayFormat);

    /**
     * @param Party $agents
     */
    public function setAgents(Party $agents);
}