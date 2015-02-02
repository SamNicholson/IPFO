<?php

function debug($var){
    echo('<pre>');
    print_r($var);
    echo('</pre>');
}

error_reporting(E_ALL);
ini_set("display_errors", 1);

date_default_timezone_set('UTC');

// This is the autoloader for all of the classes, kindly provided by Composer
$autoLoad  = "../../Vendor/autoload.php";
if(!file_exists($autoLoad)){
    echo('Unable to find autoloader, perhaps you need to generate composer?');
    die();
}
require $autoLoad;

//Establish the request object
$search = new \WAL\IPFO\IPFOSearch();

////Make the request
$search->setIPType('Patent')
       ->setNumberType('publication')
       ->setNumber('EP1452484')
       ->search();

//Get the results and do whatever you want with them!
debug($search->getResults());

//Get the results and do whatever you want with them!
debug($search->getResults());
