<?php
/**
 * @author: yevgen
 * @date: 10.11.16
 */

namespace YevgenGrytsay\Bandicoot\Tests\MergeStrategy;


use YevgenGrytsay\Bandicoot\MergeStrategy\SimpleArray;

class SimpleArrayTest extends \PHPUnit_Framework_TestCase
{
    public function testMerge() {
        $result = [];
        $merge = new SimpleArray();
        $merge->merge($result, ['value'], 'key');

        self::assertEquals(['key' => [0 => 'value']], $result);
    }
}
