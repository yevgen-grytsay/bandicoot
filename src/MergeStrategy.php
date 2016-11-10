<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 18.11.15
 */

namespace YevgenGrytsay\Bandicoot;


interface MergeStrategy
{
    /**
     * @param array $result
     * @param array $list
     * @param       $key
     * @return
     */
    public function merge(array &$result, array $list, $key);
}