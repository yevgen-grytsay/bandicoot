<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date: 12.04.16
 */

namespace YevgenGrytsay\Bandicoot\Context;


class CallableContext implements ContextInterface
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
     * @return mixed
     */
    public function run($value)
    {
        return call_user_func($this->callable, $value);
    }
}