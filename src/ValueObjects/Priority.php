<?php

namespace SNicholson\IPFO\ValueObjects;

class Priority
{

    private $number;
    private $date;
    private $kind;

    public static function fromNumber($number)
    {
        $priority = new Priority();
        $priority->setNumber($number);
        return $priority;
    }

    private function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getKind()
    {
        return $this->kind;
    }

    /**
     * @param mixed $kind
     */
    public function setKind($kind)
    {
        $this->kind = $kind;
    }
}