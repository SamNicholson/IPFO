<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 29/01/2015
 * Time: 22:48
 */

namespace IPFO\Interfaces;


interface DataMapper {

    public function setResponse($response);

    public function mapData();

    public function getMappedData();

} 