<?php
require 'Config/config.php';

//TODO oAuth!!
//

//$uspto = new \IPFO\Requests\USPTORequest();

//var_dump($uspto->sampleRequest());

header('Content-Type: application/json');

$router = new \IPFO\Services\Router();
$router->run();
