<?php
/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 05.10.15
 */

namespace YevgenGrytsay\Bandicoot;


use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;
use YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface;

class Factory
{
    /**
     * @var PropertyAccessInterface
     */
    protected $propertyAccess;
    /**
     * @var MergeStrategyInterface
     */
    protected $merge;
    /**
     * @var MergeStrategyInterface
     */
    protected $listMerge;

    /**
     * Factory constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface $propertyAccess
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface   $merge
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface   $listMerge
     */
    public function __construct(PropertyAccessInterface $propertyAccess, MergeStrategyInterface $merge, MergeStrategyInterface $listMerge)
    {
        $this->propertyAccess = $propertyAccess;
        $this->merge = $merge;
        $this->listMerge = $listMerge;
    }

    /**
     * @returns PropertyAccessInterface
     */
    public function getPropertyAccessEngine()
    {
        return $this->propertyAccess;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface
     */
    public function getMerge()
    {
        return $this->merge;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface
     */
    public function getListMerge()
    {
        return $this->listMerge;
    }
}