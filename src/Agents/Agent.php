<?php

namespace SNicholson\IPFO\Agents;

class Agent
{
    private $name;
    private $reference;
    private $email;
    private $phone;
    private $fax;
    /** @var AgentAddress */
    private $address;

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
        $this->name = $name;
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
        $this->reference = $reference;
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
        $this->email = $email;
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
        $this->phone = $phone;
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
        $this->fax = $fax;
    }

    /**
     * @return AgentAddress
     */
    public function getAddress()
    {
        if (is_null($this->address)) {
            $this->address = new AgentAddress();
        }
        return $this->address;
    }

    /**
     * @param AgentAddress $address
     */
    public function setAddress(AgentAddress $address)
    {
        $this->address = $address;
    }



    public function toArray()
    {
        return [
            'name'      => $this->getName(),
            'reference' => $this->getReference(),
            'email'     => $this->getEmail(),
            'phone'     => $this->getPhone(),
            'fax'       => $this->getFax(),
            'address'   => $this->getAddress()->toArray(),
        ];
    }
}