<?php
/**
 * @author: yevgen
 * @date: 10.11.16
 */

namespace YevgenGrytsay\Bandicoot\MergeStrategy;


class NestedArray implements ListMergeStrategyInterface
{
    /**
     * @param       $result
     * @param array $list
     * @param       $key
     */
    public function merge(&$result, array $list, $key)
    {
        foreach ($list as $value) {
            $result[] = array($key => $value);
        }
    }
}