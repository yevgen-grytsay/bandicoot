<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\ContextFilterFactory;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;
use YevgenGrytsay\Bandicoot\StackSearch;
use YevgenGrytsay\Bandicoot\Util\CallbackFilterIterator;

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
     * @var ContextFilterFactory
     */
    private $filterFactory;

    /**
     * IteratorContext constructor.
     *
     * @param \Iterator $iterator
     * @param \YevgenGrytsay\Bandicoot\Context\Context $context
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $merge
     * @param ContextFilterFactory $filterFactory
     */
    public function __construct(\Iterator $iterator, Context $context, MergeStrategyInterface $merge, ContextFilterFactory $filterFactory = null)
    {
        $this->iterator = $iterator;
        $this->context = $context;
        $this->merge = $merge;
        $this->filterFactory = $filterFactory;
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
        foreach ($this->createIterator($value, $stack) as $key => $item) {
            $result = $this->context->run($item, $stack);
            $merge->merge($return, $result, $key);
        }

        return $return;
    }

    /**
     * @param $value
     * @param \SplStack $stack
     * @return \CallbackFilterIterator|\Iterator
     * @throws \InvalidArgumentException
     */
    private function createIterator($value, \SplStack $stack)
    {
        if ($this->filterFactory) {
            $filter = $this->filterFactory->createFilter($value, new StackSearch($stack));
            return new CallbackFilterIterator($this->iterator, function($current) use($filter) {
                return $filter->accept($current);
            });
        } else {
            return $this->iterator;
        }
    }
}