<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 08/02/2016
 * Time: 20:30
 */

namespace SNicholson\IPFO\ValueObjects;


class RightType
{
    const PATENT = 'patent';
    const TRADEMARK = 'trademark';

    private $type;

    public static function trademark()
    {
        return new RightType(RightType::TRADEMARK);
    }

    public static function patent()
    {
        return new RightType(RightType::PATENT);
    }

    public static function fromString($rightType)
    {
        return new RightType($rightType);
    }

    private function __construct($type)
    {
        $this->type = $type;
    }

    public function isTrademark()
    {
        return $this->type == RightType::TRADEMARK;
    }

    public function isPatent()
    {
        return $this->type == RightType::PATENT;
    }

    public function __toString()
    {
        return $this->type;
    }
}