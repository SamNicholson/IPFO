<?php
use SNicholson\IPFO\Search;

require __DIR__ . '/start.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Establish the request object
$search = Search::patent()->byApplicationNumber('PCTGB2012052051');

$matter = $search->run();
var_dump($matter->getResult()->getInventors());