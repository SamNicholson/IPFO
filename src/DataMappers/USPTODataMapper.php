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

class USPTODataMapper extends DataMapper implements DataMapperInterface {

    protected function getGrant(){
        $re = "/(?i)#h2[\\S\\s]*?<\\/b>([\\S\\s]*?)<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $pubNo = trim($matches[1]);

        $re = "/(?i)<b>[\\s]*(\\S{0,10}? \\d{1,2}?, \\d{4}?)[\\s].*<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $pubDate = DateTime::createFromFormat('F d, Y',$matches[1])->format('Y-m-d');

        return array('number' => $pubNo, 'date' => $pubDate, 'country' => 'US');
    }

    protected function getApplication(){

        //Application number
        $re = "/(?i)Appl\\. No\\.:[\\S\\s]{0,50}<b>([\\S\\s]{0,15})<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $appNo = trim($matches[1]);

        //Application Date
        $re = "/(?i)Filed:[\\S\\s]{0,50}<b>([\\S\\s]{0,15})<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);

        $appDate = DateTime::createFromFormat('F d, Y',$matches[1])->format('Y-m-d');

        return array('number' => $appNo, 'date' => $appDate, 'country' => 'US');
    }

    protected function getParties(){
        //Inventors, get a list of all of them
        $re = "/(?i)<tr>[\\s\\S]*?Inventors:([\\s\\S]*?<\\/tr>)/";
        preg_match($re, $this->unmappedResponse, $matches);
        $inventorsHTML = trim($matches[1]);

        $re = "/(?i)<b>([\\s\\S]*)<\\/b>/";
        preg_match_all($re, $inventorsHTML, $matches);

        $inventors = array();
        foreach($matches[1] AS $sequence => $inventor){
            $inventors[] = array("name" => $inventor, "sequence" => ($sequence+1));
        }

        //Applicant
        $re = "/(?i)Assignee:[\\s\\S]*?<b>([\\s\\S]*?)<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $applicant = trim($matches[1]);

        return array("inventors" => $inventors, "applicants" => array(array("name" => $applicant, "sequence" => "1")));
    }

    protected function getTitles(){

        $re = "/(?i)<FONT size=\\\"\\+1\\\">([\\S\\s]*)<\\/FONT>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $title = trim($matches[1]);

        return array('english' => $title,'french' => '','german' => '');
    }

}
