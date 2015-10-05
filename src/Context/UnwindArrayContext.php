<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\ArrayPushMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;
use YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class UnwindArrayContext implements ContextInterface
{
    /**
     * @var string
     */
    protected $accessor;
    /**
     * @var ContextInterface
     */
    protected $context;
    /**
     * @var \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface
     */
    private $propertyAccess;

    /**
     * UnwindArrayContext constructor.
     *
     * @param string                                                          $accessor
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface $propertyAccess
     */
    public function __construct($accessor, PropertyAccessInterface $propertyAccess)
    {
        $this->accessor = $accessor;
        $this->propertyAccess = $propertyAccess;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        /**
         * @var int|string $key
         * @var ContextInterface $context
         */
        $result = array();
        $merge = $this->createMerge();
        $context = $this->getContext();
        foreach ($this->_iterator($value) as $key => $value) {
            $ret = $context->run($value);
            $merge->merge($result, $ret, $key);
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
        $data = $this->propertyAccess->getValue($value, $this->accessor);

        return new \ArrayIterator($data);
    }

    /**
     * @param \YevgenGrytsay\Bandicoot\Context\ContextInterface $context
     *
     * @return $this
     */
    public function each(ContextInterface $context = null)
    {
        $this->context = $context ?: $this->createDefaultContext();

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

    /**
     * @return MergeStrategyInterface
     */
    protected function createMerge()
    {
        return new ArrayPushMergeStrategy();
    }
}