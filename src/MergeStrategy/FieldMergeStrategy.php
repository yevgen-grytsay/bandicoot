<?php

namespace YevgenGrytsay\Bandicoot\MergeStrategy;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class FieldMergeStrategy implements MergeStrategyInterface
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
        $result[$key] = $value;
    }
}