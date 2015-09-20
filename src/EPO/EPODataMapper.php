<?php

namespace SNicholson\IPFO\EPO;

use SNicholson\IPFO\Exceptions\DataMappingException;
use SNicholson\IPFO\Interfaces\DataMapperInterface;
use SNicholson\IPFO\Abstracts\DataMapper;
use SNicholson\IPFO\Result;
use SNicholson\IPFO\Searches\SearchError;
use SNicholson\IPFO\ValueObjects\Citation;
use SNicholson\IPFO\ValueObjects\Priority;
use SNicholson\IPFO\ValueObjects\SearchSource;

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

        $result = new Result();
        $result->setSource(SearchSource::EPO());

        //Iterate through each of the documented results from the EPO
        foreach ($this->unmappedResponse['exchange-documents']['exchange-document'] as $key => $responseResult) {
            try {
                /*
                 * Titles Data
                 */
                if (isset($responseResult['bibliographic-data']['invention-title'])
                    && !isset($mappedResponse['title'])) {
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
                    && !isset($mappedResponse['citations'])) {
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
                            $this->findValueFromEPO(
                                $docDBAppInfo['date']
                            )
                        );
                    } elseif (isset($docEPOAppInfo['date'])) {
                        $result->setApplicationDate(
                            $this->findValueFromEPO(
                                $responseResult['bibliographic-data']['application-reference']['document-id'][1]['date']
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
                                            $this->findValueFromEPO(
                                                $field
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
                            foreach ($responseResult['bibliographic-data']['publication-reference']['document-id'][0] AS $fieldName => $field) {
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
                                            $this->findValueFromEPO(
                                                $field
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
                //TODO fix a bug with these getting picked up, see
                //http://ops.epo.org/3.1/rest-services/published-data/publication/epodoc/EP2285036/biblio for a sample
                if (isset($responseResult['bibliographic-data']['priority-claims'])
                    && !isset($mappedResponse['priority-claims'])) {
                    $priorityFromOffice = $responseResult['bibliographic-data']['priority-claims']['priority-claim'];
                    if (isset($priorityFromOffice[0]) && empty($result->getPriorities())) {
                        foreach ($priorityFromOffice as $pKey => $priority) {
                            $priorityObject = Priority::fromNumber(
                                $this->findValueFromEPO($priority['document-id'][0]['doc-number'])
                            );
                            $priorityObject->setDate(
                                $this->findValueFromEPO($priority['document-id'][0]['date'])
                            );
                            $priorityObject->setKind(
                                $this->findValueFromEPO($priority['document-id'][0]['kind'])
                            );
                            $result->addPriority($priorityObject);
                        }
                    } elseif (empty($result->getPriorities())) {
                        $priorityObject = Priority::fromNumber(
                            $priorityFromOffice['document-id']
                        );
                        $result->addPriority($priorityObject);
                    }
                }

                /*
                 * Inventors and Applicants Data
                 */
                if (isset($responseResult['bibliographic-data']['parties']) && !isset($mappedResponse['parties'])) {
                    /*
                     * Applicants
                     * IMPORTANT - The EPO bundles original and EPO database results together, which is confusing,
                     * we check to find the unique applicants so that we get a good clean list.
                     */
                    $mappedResponse['parties']['applicants'] = array();
                    foreach ($responseResult['bibliographic-data']['parties']['applicants']['applicant'] AS $aKey => $applicant) {
                        if (!$this->checkDuplicateParty(
                            $applicant['@sequence'],
                            $mappedResponse['parties']['applicants']
                        )
                        ) {
                            $mappedResponse['parties']['applicants'][] = array(
                                'name'     => $this->findValueFromEPO(
                                    $applicant['applicant-name']['name']
                                ),
                                'sequence' => $this->findValueFromEPO(
                                    $applicant['@sequence']
                                )
                            );
                        }
                    }
                    /*
                     * Inventors
                     * IMPORTANT - The EPO bundles original and EPO database results together, which is confusing, we check to find the unique inventors so that we get a good clean list.
                     */
                    $mappedResponse['parties']['inventors'] = array();
                    foreach ($responseResult['bibliographic-data']['parties']['inventors']['inventor'] AS $aKey => $applicant) {
                        if (!$this->checkDuplicateParty(
                            $applicant['@sequence'],
                            $mappedResponse['parties']['inventors']
                        )
                        ) {
                            $mappedResponse['parties']['inventors'][] = array(
                                'name'     => $this->findValueFromEPO(
                                    $applicant['inventor-name']['name']
                                ),
                                'sequence' => $this->findValueFromEPO(
                                    $applicant['@sequence']
                                )
                            );
                        }
                    }
                }
                $this->mappedResponse = $mappedResponse;
            } catch (DataMappingException $e) {
                $this->mappedResponse = "Failed to map data, exception occurred, ending process softly";
                $this->mapped         = true;
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

}