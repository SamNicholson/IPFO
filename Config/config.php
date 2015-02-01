<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

date_default_timezone_set('UTC');

// This is the autoloader for all of the classes, kindly provided by Composer
require __DIR__ . "/../Vendor/autoload.php";
require __DIR__ . "/../IPFO/Helpers/HelperFunctions.php";

//Establish the request object
\IPFO\Helpers\RequestObjects::check();
