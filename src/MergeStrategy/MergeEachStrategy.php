<?php
/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 05.10.15
 */

namespace YevgenGrytsay\Bandicoot\MergeStrategy;

/**
 * If $value passed to "merge" method is an array,
 * then the strategy applies specified merge strategy to each item of the array.
 * Otherwise it applies specified merge strategy once for $value.
 *
 * Class MergeEachStrategy
 * @package YevgenGrytsay\Bandicoot\MergeStrategy
 */
class MergeEachStrategy implements MergeStrategyInterface
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
     * @param $result
     * @param $value
     * @param $key
     *
     * @return mixed
     */
    public function merge(&$result, $value, $key)
    {
        if (is_array($value)) {
            foreach ($value as $item) {
                $this->itemMerge->merge($result, $item, $key);
            }
        }
        else {
            $this->itemMerge->merge($result, $value, $key);
        }
    }
}