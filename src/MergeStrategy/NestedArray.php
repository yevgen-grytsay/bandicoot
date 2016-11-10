<?php
/**
 * @author: yevgen
 * @date: 10.11.16
 */

namespace YevgenGrytsay\Bandicoot\MergeStrategy;


use YevgenGrytsay\Bandicoot\MergeStrategy;

class NestedArray implements MergeStrategy
{
    /**
     * @param array $result
     * @param array $list
     * @param $key
     */
    public function merge(array &$result, array $list, $key)
    {
        foreach ($list as $value) {
            $result[] = array($key => $value);
        }
    }
}