<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 01/02/2015
 * Time: 16:43
 */

namespace SNicholson\IPFO\Interfaces;


interface ControllerInterface {

    public function getError();

    public function numberSearch($number,$numberType);

} 