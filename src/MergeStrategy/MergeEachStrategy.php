<?php
/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 05.10.15
 */

namespace YevgenGrytsay\Bandicoot\MergeStrategy;

/**
 * Applies specified merge strategy to each item of the array.
 *
 * Class MergeEachStrategy
 * @package YevgenGrytsay\Bandicoot\MergeStrategy
 */
class MergeEachStrategy implements ListMergeStrategyInterface
{
    /**
     * @var MergeStrategyInterface
     */
    protected $itemMerge;

    /**
     * MergeEachStrategy constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $itemMerge
     */
    public function __construct(MergeStrategyInterface $itemMerge)
    {
        $this->itemMerge = $itemMerge;
    }

    /**
     * @param       $result
     * @param array $value
     * @param       $key
     */
    public function merge(&$result, array $value, $key)
    {
        foreach ($value as $item) {
            $this->itemMerge->merge($result, $item, $key);
        }
    }
}