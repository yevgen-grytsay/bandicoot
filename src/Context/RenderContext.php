<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\FieldMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

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
        $result = array();
        /**
         * @var string|int $field
         * @var ContextInterface $context
         */
        foreach ($this->config as $field => $context) {
            $res = $context->run($value);
            if ($context instanceof ListContextInterface) {
                $this->_mergeList($result, $res, $field);
            } else {
                $merge->merge($result, $res, $field);
            }
        }

        return $result;
    }
    
    protected function _mergeList(&$result, array $res, $field)
    {
        foreach ($res as $item) {
            $result[] = array($field => $item);
        }
    }

    /**
     * @return MergeStrategyInterface
     */
    protected function createMerge()
    {
        return new FieldMergeStrategy();
    }
}