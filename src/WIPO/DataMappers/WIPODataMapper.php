<?php

namespace SNicholson\IPFO\WIPO\DataMappers;

use DateTime;
use SNicholson\IPFO\Exceptions\DataMappingException;
use SNicholson\IPFO\Interfaces\DataMapperInterface;
use SNicholson\IPFO\Abstracts\DataMapper;
use SNicholson\IPFO\Result;
use SNicholson\IPFO\ValueObjects\Applicant;
use SNicholson\IPFO\ValueObjects\Inventor;
use SNicholson\IPFO\ValueObjects\Party;
use SNicholson\IPFO\ValueObjects\Priority;

class WIPODataMapper extends DataMapper implements DataMapperInterface
{

    public function mapData()
    {
        $result = new Result();
        $this->getPublication($result);
        $this->getApplication($result);
        $this->getParties($result);
        $this->getTitles($result);
        $this->getPriorities($result);

        return $result;
    }

    protected function getPublication(Result &$result)
    {

        $result->setPublicationNumber(
            $this->unmappedResponse->{'wo-international-application-status'}
                ->{'wo-bibliographic-data'}
                ->{'publication-reference'}
                ->{'document-id'}
                ->{'doc-number'}
        );

        $pubDate = $this->unmappedResponse->{'wo-international-application-status'}
            ->{'wo-bibliographic-data'}
            ->{'publication-reference'}
            ->{'document-id'}
            ->{'date'};

        $result->setPublicationDate($this->parseWIPODate($pubDate));

        $result->setPublicationCountry(
            $this->unmappedResponse->{'wo-international-application-status'}
                ->{'wo-bibliographic-data'}
                ->{'publication-reference'}
                ->{'document-id'}
                ->{'country'}
        );
    }

    protected function getApplication(Result &$result)
    {

        $result->setApplicationNumber(
            $this->unmappedResponse->{'wo-international-application-status'}
            ->{'wo-bibliographic-data'}
            ->{'application-reference'}
            ->{'document-id'}
            ->{'doc-number'}
        );

        $pubDate = $this->unmappedResponse->{'wo-international-application-status'}
            ->{'wo-bibliographic-data'}
            ->{'application-reference'}
            ->{'document-id'}
            ->{'date'};

        $result->setApplicationDate($this->parseWIPODate($pubDate));

        $result->setApplicationCountry(
            $this->unmappedResponse->{'wo-international-application-status'}
            ->{'wo-bibliographic-data'}
            ->{'application-reference'}
            ->{'document-id'}
            ->{'country'}
        );
    }

    protected function getParties(Result &$result)
    {
        $inventors = new Party();
        $applicants = new Party();
        $applicantSequence = 1;
        $inventorSequence = 1;
        foreach ($this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'parties'}->{'applicants'}->{'applicant'} AS $property => $party) {
            if (is_array($party)) {
                $applicant = new Applicant();
                $applicant->setName($party->{'addressbook'}->{'name'}->{'_'});
                $applicant->setSequence($applicantSequence);
            } else {
                if (property_exists($party, 'addressbook')) {
                    $applicant = new Applicant();
                    $applicant->setName($party->{'addressbook'}->{'name'}->{'_'});
                    $applicant->setSequence($applicantSequence);
                } else if (property_exists($party, 'name')) {
                    $applicant = new Applicant();
                    $applicant->setName($party->{'name'}->{'_'});
                    $applicant->setSequence($applicantSequence);
                }
            }

            //Are these applicant inventors?
            if (property_exists($party, 'app-type')) {
                if ($party->{'app-type'} == 'applicant-inventor') {
                    $inventor = new Inventor();
                    $inventor->setSequence($applicantSequence);
                    $inventor->setName($party->{'addressbook'}->{'name'}->{'_'});
                    $inventors->addMember($inventor);
                    $inventorSequence++;
                }
            }

            if (!empty($applicant)) {
                $applicants->addMember($applicant);
                $applicant = null;
                $applicantSequence++;
            }
        }
        $result->setApplicants($applicants);

        //Check to see if inventors entry exists
        $partyCheck = (array)$this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'parties'};
        if (!empty($partyCheck['inventors'])) {
            foreach ($this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'parties'}->{'inventors'}->{'inventor'} AS $property => $party) {
                $inventor = new Inventor();
                $inventor->setSequence($inventorSequence);
                $inventor->setName($party->{'addressbook'}->{'name'}->{'_'});
                $inventorSequence++;
            }
        }
        $result->setInventors($inventors);
    }

    protected function getPriorities(Result &$result)
    {
        //Priorities
        $priorities = [];
        $sequence = 1;
        $priorityClaim = $this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'wo-priority-info'}->{'priority-claim'};
        if (is_array($priorityClaim)) {
            foreach ($priorityClaim AS $property => $priority) {
                $priorities[] = $this->getPriorityFromWIPOFormat($priority);
                $sequence++;
            }
        } else if (is_object($priorityClaim)) {
            $priorities[] = $this->getPriorityFromWIPOFormat($priorityClaim);
        }
        $result->setPriorities(...$priorities);
    }

    private function getPriorityFromWIPOFormat($priority)
    {
        $newPriority = Priority::fromNumber($priority->{'doc-number'});
        $newPriority->setDate($this->parseWIPODate($priority->{'date'}));
        $newPriority->setCountry($priority->{'country'});
        return $newPriority;
    }

    protected function getTitles(Result &$result)
    {

    }

    private function parseWIPODate($date)
    {
        return substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
    }
}
