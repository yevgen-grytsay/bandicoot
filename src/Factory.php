<?php
/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 05.10.15
 */

namespace YevgenGrytsay\Bandicoot;


use YevgenGrytsay\Bandicoot\Context\Context;
use YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface;
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
     * @var ListMergeStrategyInterface
     */
    protected $listMerge;

    /**
     * Factory constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface $propertyAccess
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface   $merge
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface   $listMerge
     */
    public function __construct(PropertyAccessInterface $propertyAccess, MergeStrategyInterface $merge, ListMergeStrategyInterface $listMerge)
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
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface
     */
    public function getListMerge()
    {
        return $this->listMerge;
    }

    /**
     * @param Context $context
     * @param array $helperNames
     * @return Context
     * //TODO: implement
     */
    public function decorateWithHelpers(Context $context, array $helperNames)
    {
        return $context;
    }
}