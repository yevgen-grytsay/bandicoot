<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\FieldMergeStrategy;
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
     * @var string
     */
    protected $mergeType = 'field';

    /**
     * IteratorContext constructor.
     *
     * @param \Iterator                                         $iterator
     * @param \YevgenGrytsay\Bandicoot\Context\ContextInterface $context
     */
    public function __construct(\Iterator $iterator, ContextInterface $context)
    {
        $this->iterator = $iterator;
        $this->context = $context;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        $return = array();
        $merge = $this->createMerge();
        foreach ($this->iterator as $key => $item) {
            $result = $this->context->run($item);
            $merge->merge($return, $result, $key);
        }

        return $return;
}

    /**
     * @param $type
     *
     * @return $this
     */
    public function merge($type)
    {
        $this->mergeType = $type;

        return $this;
    }

    /**
     * @return MergeStrategyInterface
     */
    protected function createMerge()
    {
        return new FieldMergeStrategy();
    }
}