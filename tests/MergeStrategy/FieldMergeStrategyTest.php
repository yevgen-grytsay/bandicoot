<?php
/**
 * @author: yevgen
 */

namespace YevgenGrytsay\Bandicoot\Tests\MergeStrategy;


use YevgenGrytsay\Bandicoot\MergeStrategy\FieldMergeStrategy;

class FieldMergeStrategyTest extends \PHPUnit_Framework_TestCase
{
    public function testAddValue()
    {
        $merge = new FieldMergeStrategy();
        $result = [];
        $value = 'sample value';
        $key = 'key';

        $merge->merge($result, $value, $key);

        $this->assertEquals([$key => $value], $result);
    }

    public function testAddValueShouldOverrideValueWithSameKey()
    {
        $merge = new FieldMergeStrategy();
        $value = 'sample value';
        $key = 'key';
        $result = [$key => 'already in array'];

        $merge->merge($result, $value, $key);

        $this->assertEquals([$key => $value], $result);
    }

    public function testShouldThrowExceptionOnDuplicateKeyAndThrowOptionIsSet()
    {
        $merge = new FieldMergeStrategy();
        $merge->setThrowExceptionOnDuplicateKey(true);
        $value = 'sample value';
        $key = 'key';
        $result = [$key => 'already in array'];

        $this->setExpectedException(\RuntimeException::class);

        $merge->merge($result, $value, $key);
    }
}