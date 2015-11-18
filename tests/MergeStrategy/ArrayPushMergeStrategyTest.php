<?php
/**
 * @author: yevgen
 */

namespace YevgenGrytsay\Bandicoot\Tests\MergeStrategy;


use YevgenGrytsay\Bandicoot\MergeStrategy\ArrayPushMergeStrategy;

class ArrayPushMergeStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testPushValueToEmptyArray()
    {
        $merge = new ArrayPushMergeStrategy();
        $result = [];
        $value = ['sample value'];

        $merge->merge($result, $value, null);

        $this->assertEquals([0 => $value], $result);
    }

    public function testPushValueToNotEmptyArray()
    {
        $merge = new ArrayPushMergeStrategy();
        $valueInArray = 'already in array';
        $result = [$valueInArray];
        $value = ['sample value'];

        $merge->merge($result, $value, null);

        $this->assertEquals([0 => $valueInArray, 1 => $value], $result);
    }
}