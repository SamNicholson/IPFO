<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 08/02/2016
 * Time: 00:47
 */

namespace SNicholson\IPFO\Helpers;


class SimpleXMLToArray
{
    public static function convert($object)
    {
        return json_decode(json_encode($object), TRUE);
    }
}