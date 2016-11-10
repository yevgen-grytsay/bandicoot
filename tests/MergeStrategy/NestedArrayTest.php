<?php
/**
 * @author: yevgen
 * @date: 10.11.16
 */

namespace YevgenGrytsay\Bandicoot\Tests\MergeStrategy;


use YevgenGrytsay\Bandicoot\MergeStrategy\NestedArray;

class NestedArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testMerge() {
        $merge = new NestedArray();
        $result = [];
        $merge->merge($result, ['value'], 'key');

        $this->assertEquals([0 => ['key' => 'value']], $result);
    }
}
