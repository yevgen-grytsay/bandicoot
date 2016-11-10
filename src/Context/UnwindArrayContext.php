<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;
use YevgenGrytsay\Bandicoot\PropertyAccess\ConstantPropertyAccess;

/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 02.10.15
 */
class UnwindArrayContext implements Context
{
    /**
     * @var ConstantPropertyAccess
     */
    protected $accessor;
    /**
     * @var Context
     */
    protected $context;

    /**
     * UnwindArrayContext constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\ConstantPropertyAccess $accessor
     * @param \YevgenGrytsay\Bandicoot\Context|null         $context
     */
    public function __construct(ConstantPropertyAccess $accessor, Context $context = null)
    {
        $this->accessor = $accessor;
        $this->context = $context;
    }

    /**
     * @param $input
     *
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($input, \SplStack $stack)
    {
        /**
         * @var int|string $key
         * @var Context $context
         */
        $result = array();
        $context = $this->getContext();
        $i = 0;
        foreach ($this->_iterator($input) as $value) {
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
     * @param \YevgenGrytsay\Bandicoot\Context $context
     *
     * @return $this
     */
    public function each(Context $context)
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
     * @return \YevgenGrytsay\Bandicoot\Context
     */
    protected function createDefaultContext()
    {
        return new ValueSelfContext();
    }
}