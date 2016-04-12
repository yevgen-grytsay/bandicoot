<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;
use YevgenGrytsay\Bandicoot\PropertyAccess\ConstantPropertyAccess;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class UnwindArrayContext implements ContextInterface
{
    /**
     * @var ConstantPropertyAccess
     */
    protected $accessor;
    /**
     * @var ContextInterface
     */
    protected $context;
    /**
     * @var \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface
     */
    private $merge;

    /**
     * UnwindArrayContext constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\ConstantPropertyAccess $accessor
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface  $merge
     * @param \YevgenGrytsay\Bandicoot\Context\ContextInterface|null         $context
     */
    public function __construct(ConstantPropertyAccess $accessor, MergeStrategyInterface $merge, ContextInterface $context = null)
    {
        $this->accessor = $accessor;
        $this->merge = $merge;
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
        /**
         * @var int|string $key
         * @var ContextInterface $context
         */
        $result = array();
        $context = $this->getContext();
        $i = 0;
        foreach ($this->_iterator($value) as $value) {
            $ret = $context->run($value, $stack);
//            $this->merge->merge($result, $ret, $i);
            $result[] = $ret;
            ++$i;
        }

        return $result;
    }

    /**
     * @param $value
     *
     * @return \ArrayIterator
     */
    protected function _iterator($value)
    {
        $data = $this->accessor->getValue($value);

        return new \ArrayIterator($data);
    }

    /**
     * @param \YevgenGrytsay\Bandicoot\Context\ContextInterface $context
     *
     * @return $this
     */
    public function each(ContextInterface $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\Context\ValueSelfContext
     */
    protected function getContext()
    {
        //TODO: create object using factory or DI container
        return $this->context ?: $this->createDefaultContext();
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\Context\ContextInterface
     */
    protected function createDefaultContext()
    {
        return new ValueSelfContext();
    }
}