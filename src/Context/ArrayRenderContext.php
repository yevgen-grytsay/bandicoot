<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ArrayRenderContext implements ContextInterface
{
    /**
     * @var ContextInterface
     */
    protected $context;

    /**
     * ArrayRenderContext constructor.
     *
     * @param ContextInterface $context
     */
    public function __construct(ContextInterface $context)
    {
        $this->context = $context;
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function run($value)
    {
        return $this->context->run($value);
    }
}