<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\FieldMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;
use YevgenGrytsay\Bandicoot\MergeStrategy\NestedArrayMergeStrategy;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class RenderContext implements ContextInterface
{
    /**
     * @var string
     */
    protected $mergeType = 'field';
    /**
     * @var array
     */
    protected $config = array();

    /**
     * RenderContext constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
        $merge = $this->createMerge();
        $listMerge = $this->createListMerge();
        $result = array();
        /**
         * @var string|int $field
         * @var ContextInterface $context
         */
        foreach ($this->config as $field => $context) {
            $res = $context->run($value);
            if ($context instanceof ListContextInterface) {
                $listMerge->merge($result, $res, $field);
            } else {
                $merge->merge($result, $res, $field);
            }
        }

        return $result;
    }

    /**
     * @return MergeStrategyInterface
     */
    protected function createMerge()
    {
        return new FieldMergeStrategy();
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\NestedArrayMergeStrategy
     */
    protected function createListMerge()
    {
        return new NestedArrayMergeStrategy();
    }
}