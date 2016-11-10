<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 18.11.15
 */

namespace YevgenGrytsay\Bandicoot;


interface MergeStrategy
{
    /**
     * @param       $result
     * @param array $list
     * @param       $key
     */
    public function merge(&$result, array $list, $key);
}