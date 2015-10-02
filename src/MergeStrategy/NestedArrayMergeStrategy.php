<?php

namespace YevgenGrytsay\Bandicoot\MergeStrategy;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class NestedArrayMergeStrategy implements MergeStrategyInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * NestedArrayMergeStrategy constructor.
     *
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @param $result
     * @param $value
     * @param $key
     *
     * @return mixed
     */
    public function merge(&$result, $value, $key)
    {
        $result[] = array($this->key => $value);
    }
}