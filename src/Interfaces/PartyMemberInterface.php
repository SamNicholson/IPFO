<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 20/09/2015
 * Time: 22:46
 */

namespace SNicholson\IPFO\Interfaces;


interface PartyMemberInterface
{

    public function setSequence($sequence);

    public function getSequence();

    public function setName($name);

    public function getName();
}