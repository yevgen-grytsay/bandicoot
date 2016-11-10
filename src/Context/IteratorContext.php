<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class IteratorContext implements Context
{
    /**
     * @var \Iterator
     */
    protected $iterator;
    /**
     * @var Context
     */
    protected $context;

    /**
     * IteratorContext constructor.
     *
     * @param \Iterator                                                         $iterator
     * @param \YevgenGrytsay\Bandicoot\Context\Context                 $context
     */
    public function __construct(\Iterator $iterator, Context $context)
    {
        $this->iterator = $iterator;
        $this->context = $context;
    }

    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($value, \SplStack $stack)
    {
        $return = array();
        foreach ($this->iterator as $key => $item) {
            $return[$key] = $this->context->run($item, $stack);
        }

        return $return;
    }
}