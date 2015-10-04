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
     * @var MergeStrategyInterface
     */
    protected $merge;
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
        foreach ($this->dataSource->run($value) as $key => $item) {
            $this->merge->merge($result, $item, $key);
        }

        return $result;
    }

    /**
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $merge
     *
     * @return $this
     */
    public function merge(MergeStrategyInterface $merge)
    {
        $this->merge = $merge;

        return $this;
    }
}