<?php

namespace YevgenGrytsay\Bandicoot\MergeStrategy;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ArrayPushMergeStrategy implements MergeStrategyInterface
{
    /**
     * @param $result
     * @param $value
     * @param $key
     */
    public function merge(&$result, $value, $key)
    {
        $result[] = $value;
    }
}