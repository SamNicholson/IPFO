<?php
use SNicholson\IPFO\Parsers\RecursiveFieldSearch;

/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 07/02/2016
 * Time: 23:55
 */
class RecursiveFieldSearchTest extends PHPUnit_Framework_TestCase
{
    public function testSearchByName()
    {
        $sample = [
            'foo' => 1,
            'bar' => [
                'test' => 2,
                'third' => [
                    'four' => 4
                ]
            ]
        ];
//        $this->assertEquals(
//            1,
//            RecursiveFieldSearch::searchByName('foo', $sample)
//        );
        $this->assertEquals(
            [
                'test'  => 2,
                'third' => [
                    'four' => 4
                ]
            ],
            RecursiveFieldSearch::searchByName('bar', $sample)
        );
        $this->assertEquals(
            2,
            RecursiveFieldSearch::searchByName('test', $sample)
        );
        $this->assertEquals(
            4,
            RecursiveFieldSearch::searchByName('four', $sample)
        );
    }
}
