<?php
use SNicholson\IPFO\Search;

require __DIR__ . '/start.php';

//Establish the request object
$search = Search::patent()->byApplicationNumber('PCTGB2012052051');

var_dump($search->run());