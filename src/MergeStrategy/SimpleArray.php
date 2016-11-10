<?php
/**
 * @author: yevgen
 * @date: 10.11.16
 */

namespace YevgenGrytsay\Bandicoot\MergeStrategy;


use YevgenGrytsay\Bandicoot\MergeStrategy;

class SimpleArray implements MergeStrategy
{
    /**
     * @param array $result
     * @param array $list
     * @param $key
     */
    public function merge(array &$result, array $list, $key)
    {
        if (!array_key_exists($key, $result) && $list) {
            $result[$key] = array();
        }
        foreach ($list as $value) {
            $result[$key][] = $value;
        }
    }
}