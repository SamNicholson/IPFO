<?php

namespace SNicholson\IPFO\Searches\EPO;

use SNicholson\IPFO\Exceptions\DataMappingException;
use SNicholson\IPFO\Interfaces\DataMapperInterface;
use SNicholson\IPFO\Abstracts\DataMapper;
use WorkAnyWare\IPFO\IPRight;
use SNicholson\IPFO\Searches\SearchError;
use WorkAnyWare\IPFO\Parties\Applicant;
use WorkAnyWare\IPFO\IPRights\Citation;
use WorkAnyWare\IPFO\Parties\Inventor;
use WorkAnyWare\IPFO\Parties\Party;
use WorkAnyWare\IPFO\IPRights\Priority;
use WorkAnyWare\IPFO\IPRights\SearchSource;

class EPODataMapper extends DataMapper implements DataMapperInterface
{

    protected $unmappedResponse;
    protected $mappedResponse;

    protected $mapped = false;

    private function wasResultNotFound()
    {
        if (isset($this->unmappedResponse['ops:world-patent-data']['exchange-documents']['exchange-document']['@status'])) {
            if ($this->unmappedResponse['ops:world-patent-data']['exchange-documents']['exchange-document']['@status'] == 'not found') {
                return true;
            }
        }
        return false;
    }

    private function didSearchValidate()
    {
        //TODO refactor the whole of me into individual methods ,its a real mess in here boys!
        if (!$this->unmappedResponse) {
            return SearchError::fromString("Attempted to map data which is null");
        }

        if ($this->wasResultNotFound()) {
            return SearchError::fromString('Unable to locate Patent in the EPO Database');
        }
        return true;
    }

    protected function mapData()
    {
        $validate = $this->didSearchValidate();
        if ($validate !== true) {
            return $validate;
        }

        /*
         * IMPORTANT
         * The EPO structure differs depending on the number of results received
         * (extra array level if multiple results), this fixes that issue and
         * normalises the structure regardless of result count
         */

        if (isset($this->unmappedResponse['ops:world-patent-data'])) {
            $this->unmappedResponse = $this->unmappedResponse['ops:world-patent-data'];
        }

        if (!isset($this->unmappedResponse['exchange-documents']['exchange-document'][0])) {
            //There is only one result
            if (isset($this->unmappedResponse['exchange-documents']['exchange-document'])) {
                $this->unmappedResponse['exchange-documents']['exchange-document'] =
                    [$this->unmappedResponse['exchange-documents']['exchange-document']];
            }
        }

        $result = new IPRight();
        $result->setSource(SearchSource::EPO());

        //Iterate through each of the documented results from the EPO
        foreach ($this->unmappedResponse['exchange-documents']['exchange-document'] as $key => $responseResult) {
            try {
                /*
                 * Titles Data
                 */
                if (isset($responseResult['bibliographic-data']['invention-title'])
                    && empty($result->getEnglishTitle())) {
                    $result->setFrenchTitle($this->findValueFromEPO(
                        $responseResult['bibliographic-data']['invention-title'][0]
                    ));
                    $result->setEnglishTitle($this->findValueFromEPO(
                        $responseResult['bibliographic-data']['invention-title'][1]
                    ));
                    $result->setGermanTitle($this->findValueFromEPO(
                        $responseResult['bibliographic-data']['invention-title'][2]
                    ));
                }

                /*
                 * Citations
                 */
                if (isset($responseResult['bibliographic-data']['references-cited'])
                    && empty($result->getCitations())) {
                    foreach ($responseResult['bibliographic-data']['references-cited']['citation'] as $citation) {
                        //Type of citation
                        if (isset($citation['patcit'])) {
                            $citationObj = Citation::patent('', '', '');
                        } elseif (isset($citation['nplcit'])) {
                            $citationObj = Citation::nonPatentLiterature('', '');
                        } else {
                            $citationObj = Citation::patent('', '', '');
                        }
                        //Cited By
                        if (isset($citation['@attributes']['cited-by'])) {
                            $citationObj->setCitedBy(
                                $this->findValueFromEPO($citation['@attributes']['cited-by'])
                            );
                        }
                        //Cited in Phase
                        if (isset($citation['@attributes']['cited-phase'])) {
                            $citationObj->setCitedInPhase(
                                $this->findValueFromEPO($citation['@attributes']['cited-phase'])
                            );
                        }
                        //Sequence
                        if (isset($citation['@attributes']['sequence'])) {
                            $citationObj->setSequence(
                                $this->findValueFromEPO($citation['@attributes']['sequence'])
                            );
                        }
                        if ($citationObj->getType() == Citation::PATENT) {
                            //Number Type
                            if (isset($citation['patcit']['@attributes']['dnum-type'])) {
                                $citationObj->setNumberType(
                                    $this->findValueFromEPO(
                                        $citation['patcit']['@attributes']['dnum-type']
                                    )
                                );
                            }
                            //Number
                            if (isset($citation['patcit']['document-id'][0]['doc-number'])) {
                                if (isset($citation['patcit']['document-id'][1]['doc-number'])) {
                                    $citationObj->setNumber(
                                        $this->findValueFromEPO(
                                            $citation['patcit']['document-id'][1]['doc-number']
                                        )
                                    );
                                    $citationObj->setCountry(
                                        $this->findValueFromEPO(
                                            $citation['patcit']['document-id'][1]['country']
                                        )
                                    );
                                } else {
                                    $citationObj->setNumber(
                                        $this->findValueFromEPO(
                                            $citation['patcit']['document-id'][0]['doc-number']
                                        )
                                    );
                                    $citationObj->setCountry(
                                        $this->findValueFromEPO(
                                            substr($citation['patcit']['document-id'][0]['doc-number'], 0, 2)
                                        )
                                    );
                                }
                            }
                        } elseif ($citationObj->getType() == Citation::NON_PATENT_LITERATURE) {
                            $citationObj->setText($citation['nplcit']['text']);
                        }
                        $result->addCitation($citationObj);
                    }
                }

                /*
                 * Application Data
                 */
                if (isset($responseResult['bibliographic-data']['application-reference'])
                    && !$result->getApplicationNumber()) {
                    $docDBAppInfo = $responseResult['bibliographic-data']['application-reference']['document-id'][0];
                    $docEPOAppInfo = $responseResult['bibliographic-data']['application-reference']['document-id'][1];
                    foreach ($docDBAppInfo as $fieldName => $field) {
                        switch ($fieldName) {
                            case 'doc-number':
                                $result->setApplicationNumber($this->findValueFromEPO($field));
                                break;
                            case 'country':
                                $result->setApplicationCountry($this->findValueFromEPO($field));
                                break;
                        }
                    }

                    //Sometimes there will be no application date in the docdb database!
                    if (isset($docDBAppInfo['date'])) {
                        $result->setApplicationDate(
                            $this->transformEPODate(
                                $this->findValueFromEPO(
                                    $docDBAppInfo['date']
                                )
                            )
                        );
                    } elseif (isset($docEPOAppInfo['date'])) {
                        $result->setApplicationDate(
                            $this->transformEPODate(
                                $this->findValueFromEPO(
                                    $responseResult['bibliographic-data']['application-reference']['document-id'][1]['date']
                                )
                            )
                        );
                    }
                }

                /*
                 * Publication and Grant Data
                 */
                if (isset($responseResult['bibliographic-data']['publication-reference'])) {
                    $kindCode = $this->findValueFromEPO(
                        $responseResult['bibliographic-data']['publication-reference']['document-id'][0]['kind']
                    );

                    switch (substr($kindCode, 0, 1)) {
                        case 'A':
                            /*
                             * Publication Data
                             */
                            foreach ($responseResult['bibliographic-data']['publication-reference']['document-id'][0] as $fieldName => $field) {
                                switch ($fieldName) {
                                    case 'doc-number':
                                        $result->setPublicationNumber(
                                            $this->findValueFromEPO(
                                                $field
                                            )
                                        );
                                        break;
                                    case 'country':
                                        $result->setPublicationCountry(
                                            $this->findValueFromEPO(
                                                $field
                                            )
                                        );
                                        break;
                                    case 'date':
                                        $result->setPublicationDate(
                                            $this->transformEPODate(
                                                $this->findValueFromEPO(
                                                    $field
                                                )
                                            )
                                        );
                                        break;
                                }
                            }
                            break;
                        case 'B':
                            /*
                             * Grant Data
                             */
                            foreach ($responseResult['bibliographic-data']['publication-reference']['document-id'][0] as $fieldName => $field) {
                                switch ($fieldName) {
                                    case 'doc-number':
                                        $result->setGrantNumber(
                                            $this->findValueFromEPO(
                                                $field
                                            )
                                        );
                                        break;
                                    case 'country':
                                        $result->setGrantCountry(
                                            $this->findValueFromEPO(
                                                $field
                                            )
                                        );
                                        break;
                                    case 'date':
                                        $result->setGrantDate(
                                            $this->transformEPODate(
                                                $this->findValueFromEPO(
                                                    $field
                                                )
                                            )
                                        );
                                        break;
                                }
                            }
                            break;
                    }
                }

                /*
                 * Priorities Data
                 */
                if (isset($responseResult['bibliographic-data']['priority-claims'])) {
                    $priorityFromOffice = $responseResult['bibliographic-data']['priority-claims']['priority-claim'];
                    if (isset($priorityFromOffice[0]) && empty($result->getPriorities())) {
                        foreach ($priorityFromOffice as $pKey => $priority) {
                            $priorityObject = Priority::fromNumber(
                                $this->findValueFromEPO($priority['document-id'][0]['doc-number'])
                            );
                            $priorityObject->setDate(
                                $this->transformEPODate($this->findValueFromEPO($priority['document-id'][0]['date']))
                            );
                            if (!empty($priority['document-id'][0]['kind'])) {
                                $priorityObject->setKind(
                                    $this->findValueFromEPO($priority['document-id'][0]['kind'])
                                );
                            }
                            $result->addPriority($priorityObject);
                        }
                    } elseif (isset($priorityFromOffice['document-id'][0]) && empty($result->getPriorities())) {
                        foreach ($priorityFromOffice['document-id'] as $pKey => $priority) {
                            $priorityObject = Priority::fromNumber(
                                $this->findValueFromEPO($priority['doc-number'])
                            );
                            if (isset($priority['date'])) {
                                $priorityObject->setDate(
                                    $this->transformEPODate($this->findValueFromEPO($priority['date']))
                                );
                            }
                            if (!empty($priority['kind'])) {
                                $priorityObject->setKind(
                                    $this->findValueFromEPO($priority['kind'])
                                );
                            }
                            $result->addPriority($priorityObject);
                        }
                    } elseif (empty($result->getPriorities())) {
                        $priorityObject = Priority::fromNumber(
                            $this->findValueFromEPO($priorityFromOffice['document-id']['doc-number'])
                        );
                        $priorityObject->setDate(
                            $this->transformEPODate($this->findValueFromEPO($priorityFromOffice['document-id']['date']))
                        );
                        if (!empty($priorityFromOffice['document-id']['kind'])) {
                            $priorityObject->setKind(
                                $this->findValueFromEPO($priorityFromOffice['document-id']['kind'])
                            );
                        }
                        $result->addPriority($priorityObject);
                    }
                }

                /*
                 * Inventors and Applicants Data
                 */
                if (isset($responseResult['bibliographic-data']['parties']) && empty($result->getApplicants()->getMembers())) {
                    /*
                     * Applicants
                     * IMPORTANT - The EPO bundles original and EPO database results together, which is confusing,
                     * we check to find the unique applicants so that we get a good clean list.
                     */
                    $applicantParty = new Party();
                    $registerApplicants = $responseResult['bibliographic-data']['parties']['applicants']['applicant'];
                    foreach ($registerApplicants as $aKey => $applicant) {
                        if (!$this->checkDuplicateParty(
                            $applicant['@sequence'],
                            $applicantParty
                        )
                        ) {
                            if ($applicant['@data-format'] == 'original') {
                                $thisApp = new Applicant();
                                $thisApp->setName(
                                    $this->findValueFromEPO(
                                        $applicant['applicant-name']['name']
                                    )
                                );
                                $thisApp->setSequence($applicant['@sequence']);
                                $applicantParty->addMember($thisApp);
                            }
                        }
                    }
                    $result->setApplicants($applicantParty);
                    /*
                     * Inventors
                     * IMPORTANT - The EPO bundles original and EPO database results together,
                     * which is confusing, we check to find the unique inventors so that we get a good clean list.
                     */
                    $inventorParty = new Party();
                    $registerInventors = $responseResult['bibliographic-data']['parties']['inventors']['inventor'];
                    foreach ($registerInventors as $aKey => $inventor) {
                        if (!$this->checkDuplicateParty(
                            $inventor['@sequence'],
                            $inventorParty
                        )
                        ) {
                            //We only want the ORIGINAL inventor information
                            if ($inventor['@data-format'] == 'original') {
                                $thisInv = new Inventor();
                                $thisInv->setName(
                                    $this->findValueFromEPO(
                                        $inventor['inventor-name']['name']
                                    )
                                );
                                $thisInv->setSequence($inventor['@sequence']);
                                $inventorParty->addMember($thisInv);
                            }
                        }
                        $result->setInventors($inventorParty);
                    }
                }
            } catch (DataMappingException $e) {
                return SearchError::fromString("Failed to map data : " . $e->getMessage());
            }
        }
        return $result;
    }

    private function findValueFromEPO($possibleArray)
    {
        if (is_array($possibleArray)) {
            if (isset($possibleArray['$'])) {
                return $possibleArray['$'];
            } else {
                return false;
            }
        } else {
            return $possibleArray;
        }
    }

    private function transformEPODate($date)
    {
        return substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
    }
}