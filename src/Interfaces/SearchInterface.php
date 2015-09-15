<?php

namespace SNicholson\IPFO\Interfaces;

use SNicholson\IPFO\ValueObjects\Number;

interface SearchInterface
{

    public function getError();

    public function numberSearch(Number $number);
}
