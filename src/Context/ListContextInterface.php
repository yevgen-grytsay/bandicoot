<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\ArrayPushMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ListContextInterface implements ContextInterface
{
    /**
     * @var string
     */
    protected $mergeType = 'field';
    /**
     * @var string
     */
    protected $mergeKey;
    /**
     * @var ContextInterface
     */
    protected $dataSource;

    /**
     * ListContextInterface constructor.
     *
     * @param ContextInterface $dataSource
     */
    public function __construct(ContextInterface $dataSource)
    {
        $this->dataSource = $dataSource;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        $result = array();
        $merge = $this->createMerge();
        foreach ($this->dataSource->run($value) as $key => $item) {
            $merge->merge($result, $item, $key);
        }

        return $result;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function merge($type, $key)
    {
        $this->mergeType = $type;
        $this->mergeKey = $key;

        return $this;
    }

    /**
     * @return MergeStrategyInterface
     */
    protected function createMerge()
    {
        return new ArrayPushMergeStrategy();
    }
}