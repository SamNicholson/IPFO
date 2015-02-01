<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 15/11/2014
 * Time: 17:54
 */

namespace IPFO\Abstracts;

use IPFO\Interfaces\RequestInterface;

abstract class Request implements RequestInterface {

    public $response = false;
    public $mappings = array(
        //Bibliographic information
        'publication-reference',
        'application-reference',
        'grant-reference',
        'priority-claims',
        'parties',
        'titles',
        'citations'
    );

    public $kindCodes;
    public $mappedResponse;

    public abstract function simpleNumberSearch($number,$numberType);

    protected abstract function genRequestURI($number,$numberType);

    protected function checkDuplicatePart($newMemberSequence,$partyList){
        foreach($partyList AS $existingMember){
            if($newMemberSequence == $existingMember['sequence']){
                return true;
            }
        }
        return false;
    }
}