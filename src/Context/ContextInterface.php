<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
interface ContextInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value);
}