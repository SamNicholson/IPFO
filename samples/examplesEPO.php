<?php
use SNicholson\IPFO\Search;

require __DIR__ . '/start.php';

//Establish the request object
$search = Search::patent()->byPublicationNumber('EP1452484');

var_dump($search->search());