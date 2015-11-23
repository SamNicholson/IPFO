<?php
use SNicholson\IPFO\Search;

require __DIR__ . '/start.php';

//Establish the request object
$search = Search::patent()->byApplicationNumber('PCT/IB2013/051716');

var_dump($search->run());