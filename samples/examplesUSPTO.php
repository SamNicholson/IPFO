<?php
use SNicholson\IPFO\Search;

require __DIR__ . '/start.php';

//Establish the request object
$search = Search::patent()->byPublicationNumber('US13335524');

var_dump($search->run());
