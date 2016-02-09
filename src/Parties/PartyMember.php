<?php

namespace SNicholson\IPFO\Parties;

abstract class PartyMember implements PartyMemberInterface
{
    protected $sequence = '';
    protected $name = '';
    protected $reference = '';
    protected $email = '';
    protected $phone = '';
    protected $fax = '';
    /** @var PartyMemberAddress */
    protected $address;
    protected $nationality = '';
    protected $domicile = '';

    public function __construct()
    {
        $this->address = new PartyMemberAddress();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = (string) $name;
    }

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = (string) $reference;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = (string) $phone;
    }

    /**
     * @return mixed
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param mixed $fax
     */
    public function setFax($fax)
    {
        $this->fax = (string) $fax;
    }

    /**
     * @return PartyMemberAddress
     */
    public function &getAddress()
    {
        if (is_null($this->address)) {
            $this->address = new PartyMemberAddress();
        }
        return $this->address;
    }

    /**
     * @param PartyMemberAddress $address
     */
    public function setAddress(PartyMemberAddress $address)
    {
        $this->address = $address;
    }

    public function toArray()
    {
        return [
            'name'        => $this->getName(),
            'sequence'    => $this->getSequence(),
            'reference'   => $this->getReference(),
            'email'       => $this->getEmail(),
            'phone'       => $this->getPhone(),
            'fax'         => $this->getFax(),
            'address'     => $this->getAddress()->toArray(),
            'nationality' => $this->getNationality(),
            'domicile'    => $this->getDomicile()
        ];
    }

    public function setSequence($sequence)
    {
        $this->sequence = (string) $sequence;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    /**
     * @return mixed
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * @param mixed $nationality
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
    }

    /**
     * @return mixed
     */
    public function getDomicile()
    {
        return $this->domicile;
    }

    /**
     * @param mixed $domicile
     */
    public function setDomicile($domicile)
    {
        $this->domicile = $domicile;
    }
}