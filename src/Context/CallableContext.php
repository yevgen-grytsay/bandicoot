<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date: 12.04.16
 */
namespace YevgenGrytsay\Bandicoot\Context;

use YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\StackSearch;

class CallableContext implements Context
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * CallableContext constructor.
     * @param callable $callable
     * @throws \InvalidArgumentException
     */
    public function __construct($callable)
    {
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException(
                sprintf('Argument must be callable. "%s" given.', gettype($callable))
            );
        }
        $this->callable = $callable;
    }

    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($value, \SplStack $stack)
    {
        return call_user_func($this->callable, $value, new StackSearch($stack));
    }
}