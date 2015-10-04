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
     * @var MergeStrategyInterface
     */
    protected $merge;
    /**
     * @var MergeStrategyInterface
     */
    protected $listMerge;
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
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $merge
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $listMerge
     *
     * @return $this
     */
    public function merge(MergeStrategyInterface $merge, MergeStrategyInterface $listMerge)
    {
        $this->merge = $merge;
        $this->listMerge = $listMerge;

        return $this;
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function run($value)
    {
        $result = array();
        /**
         * @var string|int $field
         * @var ContextInterface $context
         */
        foreach ($this->config as $field => $context) {
            $res = $context->run($value);
            if ($context instanceof ListContextInterface) {
                $this->listMerge->merge($result, $res, $field);
            } else {
                $this->merge->merge($result, $res, $field);
            }
        }

        return $result;
    }
}