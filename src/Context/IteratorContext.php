<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

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
     * @var \YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface
     */
    private $merge;

    /**
     * IteratorContext constructor.
     *
     * @param \Iterator                                                         $iterator
     * @param \YevgenGrytsay\Bandicoot\Context\Context                 $context
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $merge
     */
    public function __construct(\Iterator $iterator, Context $context, MergeStrategyInterface $merge)
    {
        $this->iterator = $iterator;
        $this->context = $context;
        $this->merge = $merge;
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
        $merge = $this->merge;
        foreach ($this->iterator as $key => $item) {
            $result = $this->context->run($item, $stack);
            $merge->merge($return, $result, $key);
        }

        return $return;
    }
}