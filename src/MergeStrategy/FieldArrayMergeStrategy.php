<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 18.11.15
 */

namespace YevgenGrytsay\Bandicoot\MergeStrategy;


class FieldArrayMergeStrategy implements MergeStrategyInterface
{
    /**
     * @param $result
     * @param $value
     * @param $key
     */
    public function merge(&$result, $value, $key)
    {
        if (!array_key_exists($key, $result)) {
            $result[$key] = array();
        }
        $result[$key][] = $value;
    }
}