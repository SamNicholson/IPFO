<?php

/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 06/02/2016
 * Time: 22:05
 */
class AgentTest extends PHPUnit_Framework_TestCase
{
    public function testToArray()
    {
        $agent = new \SNicholson\IPFO\Agents\Agent();
        $address = new \SNicholson\IPFO\Agents\AgentAddress();
        $address->setAddress('1 some street');
        $agent->setAddress($address);
        $agent->setName('Bob Jones');
        $agent->setEmail('bob@jones.com');

        $this->assertEquals(
            [
                'name'      => 'Bob Jones',
                'reference' => '',
                'email'     => 'bob@jones.com',
                'phone'     => '',
                'fax'       => '',
                'address'   => [
                    'address'  => '1 some street',
                    'postCode' => '',
                    'country'  => ''
                ],
            ],
            $agent->toArray()
        );

    }
}
