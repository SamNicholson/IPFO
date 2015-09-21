<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 01/02/2015
 * Time: 08:48
 */

namespace SNicholson\IPFO\DataMappers;

use DateTime;
use SNicholson\IPFO\Exceptions\DataMappingException;
use SNicholson\IPFO\Interfaces\DataMapperInterface;
use SNicholson\IPFO\Abstracts\DataMapper;

class WIPODataMapper extends DataMapper implements DataMapperInterface {

    protected function getPublication(){

        $pubNo = $this->unmappedResponse->{'wo-international-application-status'}
                        ->{'wo-bibliographic-data'}
                        ->{'publication-reference'}
                        ->{'document-id'}
                        ->{'doc-number'};

        $pubDate = $this->unmappedResponse->{'wo-international-application-status'}
                        ->{'wo-bibliographic-data'}
                        ->{'publication-reference'}
                        ->{'document-id'}
                        ->{'date'};

        $pubDate = $this->parseWIPODate($pubDate);

        $country = $this->unmappedResponse->{'wo-international-application-status'}
                        ->{'wo-bibliographic-data'}
                        ->{'publication-reference'}
                        ->{'document-id'}
                        ->{'country'};

        return array('number' => $pubNo, 'date' => $pubDate, 'country' => $country);
    }

    protected function getApplication(){

        $pubNo = $this->unmappedResponse->{'wo-international-application-status'}
            ->{'wo-bibliographic-data'}
            ->{'application-reference'}
            ->{'document-id'}
            ->{'doc-number'};

        $pubDate = $this->unmappedResponse->{'wo-international-application-status'}
            ->{'wo-bibliographic-data'}
            ->{'application-reference'}
            ->{'document-id'}
            ->{'date'};

        $pubDate = $this->parseWIPODate($pubDate);

        $country = $this->unmappedResponse->{'wo-international-application-status'}
            ->{'wo-bibliographic-data'}
            ->{'application-reference'}
            ->{'document-id'}
            ->{'country'};

        return array('number' => $pubNo, 'date' => $pubDate, 'country' => $country);
    }

    protected function getParties(){

        //Applicants
        $applicants = array();
        $sequence = 1;
        foreach($this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'parties'}->{'applicants'}->{'applicant'} AS $property => $party){
            if(is_array($party)){
                $applicants[] = array('name' => $party->{'addressbook'}->{'name'}->{'_'}, 'sequence' => $sequence);
            }
            else {
                if(property_exists($party,'addressbook')){
                    echo('yep');
                    $applicants[] = array('name' => $party->{'addressbook'}->{'name'}->{'_'}, 'sequence' => $sequence);
                }
                else if(property_exists($party,'name')){
                    $applicants[] = array('name' => $party->{'name'}->{'_'}, 'sequence' => $sequence);
                }
            }
            $sequence++;
        }

        //Inventors
        $inventors = array();
        $sequence = 1;

        //Check to see if inventors entry exists
        $partyCheck = (array) $this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'parties'};
        if(!empty($partyCheck['inventors'])) {
            foreach ($this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'parties'}->{'inventors'}->{'Inventor'} AS $property => $party) {
                if (is_array($party)) {
                    $inventors[] = array('name'     => $party->{'addressbook'}->{'name'}->{'_'},
                                         'sequence' => $sequence
                    );
                }
                else {

                    $inventors[] = array('name' => $party->{'addressbook'}->{'name'}->{'_'}, 'sequence' => $sequence);
                }
                $sequence++;
            }
        }

        //Inventors
        return array("applicants" => $applicants, "inventors" => $inventors);
    }

    protected function getPriorities(){

        //Priorities
        $priorities = array();
        $sequence = 1;
        foreach($this->unmappedResponse->{'wo-international-application-status'}->{'wo-bibliographic-data'}->{'wo-priority-info'}->{'priority-claim'} AS $property => $priority){
            $date = $this->parseWIPODate($priority->{'date'});
            $priorities[] = array('date' => $date, 'number' => $priority->{'doc-number'}, "country" => $priority->{'country'});
            $sequence++;
        }

        return $priorities;
    }

    protected function getTitles(){

    }

    private function parseWIPODate($date){
        return substr($date,0,4).'-'.substr($date,4,2).'-'.substr($date,6,2);
    }

}
