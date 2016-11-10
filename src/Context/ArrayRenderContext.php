<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 02.10.15
 */
class ArrayRenderContext implements Context
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * ArrayRenderContext constructor.
     *
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return array
     */
    public function run($value, \SplStack $stack)
    {
        return $this->context->run($value, $stack);
    }
}