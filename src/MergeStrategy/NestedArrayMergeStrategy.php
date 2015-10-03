<?php

namespace YevgenGrytsay\Bandicoot\MergeStrategy;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class NestedArrayMergeStrategy implements MergeStrategyInterface
{
    /**
     * @param $result
     * @param $value
     * @param $key
     *
     * @return mixed
     */
    public function merge(&$result, $value, $key)
    {
        $result[] = array($key => $value);
    }
}