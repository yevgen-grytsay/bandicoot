<?php

namespace YevgenGrytsay\Bandicoot\MergeStrategy;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class FieldMergeStrategy implements MergeStrategyInterface
{
    /**
     * @var bool
     */
    protected $throwExceptionOnDuplicateKey = false;

    /**
     * @param $result
     * @param $value
     * @param $key
     *
     * @return mixed
     */
    public function merge(&$result, $value, $key)
    {
        if ($this->throwExceptionOnDuplicateKey && array_key_exists($key, $result)) {
            throw new \RuntimeException(sprintf('Duplicate key "%s"', $key));
        }

        $result[$key] = $value;
    }

    /**
     * @return boolean
     */
    public function isThrowExceptionOnDuplicateKey()
    {
        return $this->throwExceptionOnDuplicateKey;
    }

    /**
     * @param boolean $throw
     */
    public function setThrowExceptionOnDuplicateKey($throw)
    {
        $this->throwExceptionOnDuplicateKey = $throw;
    }
}