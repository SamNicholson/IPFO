<?php
use SNicholson\IPFO\Search;

require __DIR__ . '/start.php';

//Run a search for Patents on a Publication Number
$search = Search::patent()->byPublicationNumber('EP1452484')->run();

//Yes, it was that easy
if ($search->getSuccess()) {
    foreach($search->getResult()->getInventors(true) as $inventor) {
        echo $inventor->getName();
    }
}