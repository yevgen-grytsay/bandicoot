<?php
/**
 * @author: yevgen
 */

namespace YevgenGrytsay\Bandicoot\Tests\MergeStrategy;


use YevgenGrytsay\Bandicoot\MergeStrategy\NestedArrayMergeStrategy;

class NestedArrayMergeStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testAddValue()
    {
        $merge = new NestedArrayMergeStrategy();
        $result = [];
        $value = 'sample value';
        $key = 'key';

        $merge->merge($result, $value, $key);

        $this->assertEquals([0 => [$key => $value]], $result);
    }
    public function testAddValueNotEmptyArray()
    {
        $merge = new NestedArrayMergeStrategy();
        $valueInArray = 'already in array';
        $result = [$valueInArray];
        $value = 'sample value';
        $key = 'key';

        $merge->merge($result, $value, $key);

        $this->assertEquals([0 => $valueInArray, 1 => [$key => $value]], $result);
    }
}
