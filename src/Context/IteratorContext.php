<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class IteratorContext implements ContextInterface
{
    /**
     * @var \Iterator
     */
    protected $iterator;
    /**
     * @var ContextInterface
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
     * @param \YevgenGrytsay\Bandicoot\Context\ContextInterface                 $context
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $merge
     */
    public function __construct(\Iterator $iterator, ContextInterface $context, MergeStrategyInterface $merge)
    {
        $this->iterator = $iterator;
        $this->context = $context;
        $this->merge = $merge;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        $return = array();
        $merge = $this->merge;
        foreach ($this->iterator as $key => $item) {
            $result = $this->context->run($item);
            $merge->merge($return, $result, $key);
        }

        return $return;
    }
}