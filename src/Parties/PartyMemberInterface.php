<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 20/09/2015
 * Time: 22:46
 */

namespace SNicholson\IPFO\Parties;


use SNicholson\IPFO\Parties\PartyMemberAddress;

interface PartyMemberInterface
{

    public function setSequence($sequence);

    public function getSequence();

    public function setName($name);

    public function getName();

    public function setReference($reference);

    public function getReference();

    public function setEmail($email);

    public function getEmail();

    public function setPhone($phone);

    public function getPhone();

    public function setFax($fax);

    public function getFax();

    public function setAddress(PartyMemberAddress $address);

    public function getAddress();
}