<?php

namespace SNicholson\IPFO\Searches;

class SearchError
{

    private $message;

    public static function fromString($message)
    {
        return new SearchError($message);
    }

    private function __construct($errorMessage)
    {
        $this->message = $errorMessage;
    }

    public function __toString()
    {
        return $this->message;
    }
}