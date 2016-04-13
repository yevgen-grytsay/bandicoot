<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 13.04.16
 */

namespace YevgenGrytsay\Bandicoot\Context;


class ConstantContext implements Context
{
    /**
     * @var mixed
     */
    private $value;

    /**
     * ConstantContext constructor.
     * @param mixed $value
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($value, \SplStack $stack)
    {
        return $this->value;
    }
}