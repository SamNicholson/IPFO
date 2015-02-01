<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 15/11/2014
 * Time: 15:08
 */

namespace IPFO\Helpers;


class Response {

    private $success;

    static function show($output, $success, $errorString = ''){

        if(!is_bool($success)){
            $success = false;
            $errorString = 'Success type specified for response was not a valid boolean.';
        }

        $response = array('success' => $success);

        if(!$success){
            $response['error'] = $errorString;
        }
        else {
            $response['data'] = $output;
        }

        header('Content-Type: application/json');
        echo(json_encode($response));
    }

} 