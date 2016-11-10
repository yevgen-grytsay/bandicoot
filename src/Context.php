<?php

namespace YevgenGrytsay\Bandicoot;

/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 02.10.15
 */
interface Context
{
    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     * @throws \Exception
     */
    public function run($value, \SplStack $stack);
}