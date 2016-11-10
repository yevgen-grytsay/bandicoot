<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 02.10.15
 */
class ValueSelfContext implements Context
{
    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($value, \SplStack $stack)
    {
        return $value;
    }
}