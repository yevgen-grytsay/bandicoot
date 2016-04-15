<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 15.04.16
 */

namespace YevgenGrytsay\Bandicoot;


interface ContextFilterFactory
{
    /**
     * @param $contextData
     * @param StackSearch $search
     * @return ContextFilter
     */
    public function createFilter($contextData, StackSearch $search);
}