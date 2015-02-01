<?php

namespace IPFO\Helpers;

class RequestObjects {

    public static $request = array();

    public static function check(){
        static::$request['get'] = $_GET;
        static::$request['post'] = $_POST;
        unset($_GET,$_POST);
    }
}