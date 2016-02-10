<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 09/02/2016
 * Time: 23:49
 */

namespace SNicholson\IPFO\Helpers;


class RightNumber
{

    const PUBLICATION = 'publication';
    const APPLICATION = 'application';

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
     *
     * @param $number
     *
     * @return RightNumber
     */
    public static function application($number)
    {
        return new RightNumber('application', $number);
    }

    /**
     * Returns a new publication number
     *
     * @param $number
     *
     * @return RightNumber
     */
    public static function publication($number)
    {
        return new RightNumber('publication', $number);
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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Returns the number string e.g GB12345
     * @return string
     */
    public function getNumberString()
    {
        return $this->numberString;
    }

    /**
     * Returns the number part when utilised as a string
     * @return string
     */
    public function __toString()
    {
        return $this->numberString;
    }
}