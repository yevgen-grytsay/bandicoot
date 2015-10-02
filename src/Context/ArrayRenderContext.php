<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ArrayRenderContext implements ContextInterface
{
    protected $mergeType = 'field';
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
     * @param $value
     *
     * @return array
     */
    public function run($value)
    {
        return $this->context->run($value);
    }

    /**
     * @return MergeStrategyInterface
     */
    protected function createMerge()
    {
        return new FieldMergeStrategy();
    }
}