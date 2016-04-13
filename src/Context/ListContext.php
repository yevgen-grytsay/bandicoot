<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\ArrayPushMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ListContext implements Context
{
    /**
     * @var Context
     */
    protected $dataSource;
    /**
     * @var \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface
     */
    private $merge;

    /**
     * ListContext constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\Context\Context                 $dataSource
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $merge
     */
    public function __construct(Context $dataSource, MergeStrategyInterface $merge)
    {
        $this->dataSource = $dataSource;
        $this->merge = $merge;
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
}