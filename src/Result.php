<?php

namespace SNicholson\IPFO;

use SNicholson\IPFO\Agents\Agent;
use SNicholson\IPFO\ValueObjects\Applicant;
use SNicholson\IPFO\ValueObjects\Citation;
use SNicholson\IPFO\ValueObjects\Inventor;
use SNicholson\IPFO\ValueObjects\Party;
use SNicholson\IPFO\ValueObjects\Priority;
use SNicholson\IPFO\ValueObjects\SearchSource;

class Result
{

    private $applicationDate;
    private $applicationCountry;
    private $applicationNumber;

    private $publicationDate;
    private $publicationCountry;
    private $publicationNumber;

    private $grantDate;
    private $grantCountry;
    private $grantNumber;

    /** @var Party */
    private $inventors;
    /** @var Party */
    private $applicants;

    private $englishTitle;
    private $frenchTitle;
    private $germanTitle;

    private $citations = [];

    private $priorities;

    private $languageOfFiling;

    /** @var  SearchSource */
    private $source;

    /** @var Agent */
    private $agent;

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getApplicationDate()
    {
        return $this->applicationDate;
    }

    /**
     * @param mixed $applicationDate
     */
    public function setApplicationDate($applicationDate)
    {
        $this->applicationDate = $applicationDate;
    }

    /**
     * @return mixed
     */
    public function getApplicationCountry()
    {
        return $this->applicationCountry;
    }

    /**
     * @param mixed $applicationCountry
     */
    public function setApplicationCountry($applicationCountry)
    {
        $this->applicationCountry = $applicationCountry;
    }

    /**
     * @return mixed
     */
    public function getApplicationNumber()
    {
        return $this->applicationNumber;
    }

    /**
     * @param mixed $applicationNumber
     */
    public function setApplicationNumber($applicationNumber)
    {
        $this->applicationNumber = $applicationNumber;
    }

    /**
     * @return mixed
     */
    public function getPublicationDate()
    {
        return $this->publicationDate;
    }

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate)
    {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return mixed
     */
    public function getPublicationCountry()
    {
        return $this->publicationCountry;
    }

    /**
     * @param mixed $publicationCountry
     */
    public function setPublicationCountry($publicationCountry)
    {
        $this->publicationCountry = $publicationCountry;
    }

    /**
     * @return mixed
     */
    public function getPublicationNumber()
    {
        return $this->publicationNumber;
    }

    /**
     * @param mixed $publicationNumber
     */
    public function setPublicationNumber($publicationNumber)
    {
        $this->publicationNumber = $publicationNumber;
    }

    /**
     * @return mixed
     */
    public function getGrantDate()
    {
        return $this->grantDate;
    }

    /**
     * @param mixed $grantDate
     */
    public function setGrantDate($grantDate)
    {
        $this->grantDate = $grantDate;
    }

    /**
     * @return mixed
     */
    public function getGrantCountry()
    {
        return $this->grantCountry;
    }

    /**
     * @param mixed $grantCountry
     */
    public function setGrantCountry($grantCountry)
    {
        $this->grantCountry = $grantCountry;
    }

    /**
     * @return mixed
     */
    public function getGrantNumber()
    {
        return $this->grantNumber;
    }

    /**
     * @param mixed $grantNumber
     */
    public function setGrantNumber($grantNumber)
    {
        $this->grantNumber = $grantNumber;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return mixed
     */
    public function getApplicants($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = [];
            /** @var Applicant $applicant */
            foreach ($this->applicants->getMembers() as $applicant) {
                $arrayToReturn[] = ['name' => $applicant->getName(), 'sequence' =>$applicant->getSequence()];
            }
            return $arrayToReturn;
        }
        return $this->applicants;
    }

    /**
     * @param mixed $applicants
     */
    public function setApplicants(Party $applicants)
    {
        $this->applicants = $applicants;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return Party|Inventor[]
     */
    public function getInventors($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = [];
            /** @var Inventor $inventor */
            foreach ($this->inventors->getMembers() as $inventor) {
                $arrayToReturn[] = ['name' => $inventor->getName(), 'sequence' =>$inventor->getSequence()];
            }
            return $arrayToReturn;
        }
        return $this->inventors;
    }

    /**
     * @param mixed $inventors
     */
    public function setInventors(Party $inventors)
    {
        $this->inventors = $inventors;
    }

    /**
     * @return mixed
     */
    public function getEnglishTitle()
    {
        return $this->englishTitle;
    }

    /**
     * @param mixed $englishTitle
     */
    public function setEnglishTitle($englishTitle)
    {
        $this->englishTitle = $englishTitle;
    }

    /**
     * @return mixed
     */
    public function getFrenchTitle()
    {
        return $this->frenchTitle;
    }

    /**
     * @param mixed $frenchTitle
     */
    public function setFrenchTitle($frenchTitle)
    {
        $this->frenchTitle = $frenchTitle;
    }

    /**
     * @return mixed
     */
    public function getGermanTitle()
    {
        return $this->germanTitle;
    }

    /**
     * @param mixed $germanTitle
     */
    public function setGermanTitle($germanTitle)
    {
        $this->germanTitle = $germanTitle;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return mixed
     */
    public function getCitations($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = '';
            /** @var Citation $citation */
            foreach ($this->citations as $citation) {
                if ($citation->getType() == Citation::PATENT) {
                    $arrayToReturn[] = [
                        'type'     => $citation->getType(),
                        'number'   => $citation->getNumber(),
                        'country'  => $citation->getCountry(),
                        'cited-by' => $citation->getCitedBy(),
                        'date'     => $citation->getCitationDate(),
                    ];
                } else {
                    $arrayToReturn[] = [
                        'type'     => $citation->getType(),
                        'text'     => $citation->getText(),
                        'country'  => $citation->getCountry(),
                        'cited-by' => $citation->getCitedBy(),
                        'date'     => $citation->getCitationDate(),
                    ];
                }
            }
            return $arrayToReturn;
        }
        return $this->citations;
    }

    public function addCitation(Citation $citation)
    {
        $this->citations[] = $citation;
    }

    /**
     * @param mixed $citations
     */
    public function setCitations(Citation ...$citations)
    {
        $this->citations = $citations;
    }

    /**
     * @param bool $inArrayFormat
     *
     * @return mixed
     */
    public function getPriorities($inArrayFormat = false)
    {
        if ($inArrayFormat) {
            $arrayToReturn = '';
            /** @var Priority $priority */
            foreach ($this->priorities as $priority) {
                $arrayToReturn[] = [
                    'number'  => $priority->getNumber(),
                    'date'    => $priority->getDate(),
                    'country' => $priority->getCountry(),
                    'kind'    => $priority->getKind()
                ];
            }
            return $arrayToReturn;
        }
        return $this->priorities;
    }

    /**
     * @param mixed $priorities
     */
    public function setPriorities(Priority ...$priorities)
    {
        $this->priorities = $priorities;
    }

    public function addPriority(Priority $priority)
    {
        $this->priorities[] = $priority;
    }

    public function toArray()
    {
        return [
            'source'           => $this->getSource()->__toString(),
            'titles'           => [
                'english' => $this->getEnglishTitle(),
                'french'  => $this->getFrenchTitle(),
                'german'  => $this->getGermanTitle()
            ],
            'application'      => [
                'country' => $this->getApplicationCountry(),
                'date'    => $this->getApplicationDate(),
                'number'  => $this->getApplicationNumber()
            ],
            'publication'      => [
                'country' => $this->getPublicationCountry(),
                'date'    => $this->getPublicationDate(),
                'number'  => $this->getPublicationNumber()
            ],
            'grant'            => [
                'country' => $this->getGrantCountry(),
                'date'    => $this->getGrantDate(),
                'number'  => $this->getGrantNumber()
            ],
            'priorities'       => $this->getPriorities(true),
            'applicants'       => $this->getApplicants(true),
            'inventors'        => $this->getInventors(true),
            'citations'        => $this->getCitations(true),
            'languageOfFiling' => $this->getLanguageOfFiling(),
            'agent'            => $this->getAgent(true)
        ];
    }

    /**
     * @return mixed
     */
    public function getLanguageOfFiling()
    {
        return $this->languageOfFiling;
    }

    /**
     * @param mixed $languageOfFiling
     */
    public function setLanguageOfFiling($languageOfFiling)
    {
        $this->languageOfFiling = $languageOfFiling;
    }

    /**
     * @param $asArray
     *
     * @return Agent
     */
    public function getAgent($asArray)
    {
        if ($this->agent instanceOf Agent && $asArray) {
            return $this->agent->toArray();
        }
        return $this->agent;
    }

    /**
     * @param Agent $agent
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
    }
}
