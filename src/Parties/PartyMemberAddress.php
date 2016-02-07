<?php

namespace SNicholson\IPFO\Parties;

class PartyMemberAddress
{
    private $address;
    private $postCode;
    private $country;

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param mixed $postCode
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
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

    public function toArray() {
        return [
            'address'  => $this->getAddress(),
            'country'  => $this->getCountry(),
            'postCode' => $this->getPostCode()
        ];
    }
}