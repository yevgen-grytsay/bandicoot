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
     * @param       $result
     * @param array $list
     * @param       $key
     */
    public function merge(&$result, array $list, $key)
    {
        foreach ($list as $value) {
            if (!array_key_exists($key, $result)) {
                $result[$key] = array();
            }
            $result[$key][] = $value;
        }
    }
}