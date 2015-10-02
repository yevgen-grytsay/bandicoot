<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ValueSelfContext implements ContextInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        return $value;
    }
}