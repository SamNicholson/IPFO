<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 16/11/14
 * Time: 23:53
 */

namespace IPFO\Exceptions;


class InvalidAddressException extends \Exception{

        function __construct($message){
            echo($message);
            parent::__construct();
        }

} 