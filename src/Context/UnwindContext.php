<?php

namespace YevgenGrytsay\Bandicoot\Context;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class UnwindContext implements Context
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
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($value, \SplStack $stack)
    {
        /**
         * @var int|string $key
         * @var Context $context
         */
        $result = array();
        $merge = $this->createMerge();
        foreach ($this->config as $key => $context) {
            $ret = $context->run($value, $stack);
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