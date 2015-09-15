<?php

namespace SNicholson\IPFO\ValueObjects;

/**
 * Class Number
 * @package SNicholson\IPFO\ValueObjects
 */
class Number
{
    /**
     * The Number String, which houses the primary value e.g. GB12345
     * @var
     */
    private $numberString;
    /**
     * The number type e.g. application
     * @var
     */
    private $type;

    /**
     * Returns a new application number
     * @param $number
     *
     * @return Number
     */
    public static function application($number)
    {
        return new Number('application', $number);
    }

    /**
     * Returns a new publication number
     * @param $number
     *
     * @return Number
     */
    public static function publication($number)
    {
        return new Number('publication', $number);
    }

    /**
     * A private constructor
     * @param $type
     * @param $number
     */
    private function __construct($type, $number)
    {
        $this->numberString = $number;
        $this->type = $type;
    }

    /**
     * Returns the number type e.g. application
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the number string e.g GB12345
     * @return mixed
     */
    public function getNumberString()
    {
        return $this->numberString;
    }
}