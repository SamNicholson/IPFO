<?php
use SNicholson\IPFO\Helpers\IPFOXML;
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
        $sample = '<base>
                        <foo>1</foo>
                        <bar>
                            <test>2</test>
                            <third>
                                <four>4</four>
                            </third>
                        </bar>
                    </base>';
        $sample = simplexml_load_string($sample, IPFOXML::class);

        $this->assertEquals(
            1,
            RecursiveFieldSearch::searchByName('foo', $sample)
        );
        $this->assertEquals(
            (array) simplexml_load_string(
                '<bar>
                    <test>2</test>
                    <third>
                        <four>4</four>
                    </third>
                </bar>', IPFOXML::class
            ),
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
