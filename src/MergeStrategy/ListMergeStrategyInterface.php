<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 18.11.15
 */

namespace YevgenGrytsay\Bandicoot\MergeStrategy;


interface ListMergeStrategyInterface
{
    /**
     * @param       $result
     * @param array $list
     * @param       $key
     */
    public function merge(&$result, array $list, $key);
}