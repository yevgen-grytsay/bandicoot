<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class UnwindContext implements ContextInterface
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
     * UnwindContext constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
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
        foreach ($this->config as $key => $context) {
            $ret = $context->run($value);
            $merge->merge($result, $ret, $key);
        }

        return $result;
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