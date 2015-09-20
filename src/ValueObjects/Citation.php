<?php

namespace SNicholson\IPFO\ValueObjects;

class Citation
{

    const PATENT = 'patent';
    const NON_PATENT_LITERATURE = 'npl';

    private $type;
    private $number;
    private $numberType;
    private $country;
    private $text;
    private $citedBy;
    private $citedInPhase;
    private $sequence;

    public static function patent($number, $county, $citedBy)
    {
        $citation = new Citation(Citation::PATENT);
        $citation->setNumber($number);
        $citation->setCitedBy($citedBy);
        $citation->setCountry($county);
        return $citation;
    }

    public static function nonPatentLiterature($text, $citedBy)
    {
        $citation = new Citation(Citation::NON_PATENT_LITERATURE);
        $citation->setText($text);
        $citation->setCitedBy($citedBy);
        return $citation;
    }

    private function __construct($type)
    {
        $this->setType($type);
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getCitedBy()
    {
        return $this->citedBy;
    }

    /**
     * @param mixed $citedBy
     */
    public function setCitedBy($citedBy)
    {
        $this->citedBy = $citedBy;
    }

    /**
     * @return mixed
     */
    public function getCitedInPhase()
    {
        return $this->citedInPhase;
    }

    /**
     * @param mixed $citedInPhase
     */
    public function setCitedInPhase($citedInPhase)
    {
        $this->citedInPhase = $citedInPhase;
    }

    /**
     * @return mixed
     */
    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @param mixed $sequence
     */
    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    /**
     * @return mixed
     */
    public function getNumberType()
    {
        return $this->numberType;
    }

    /**
     * @param mixed $numberType
     */
    public function setNumberType($numberType)
    {
        $this->numberType = $numberType;
    }
}
