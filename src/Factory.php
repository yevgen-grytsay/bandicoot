<?php
/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 05.10.15
 */
namespace YevgenGrytsay\Bandicoot;

use YevgenGrytsay\Bandicoot\Context\Context;
use YevgenGrytsay\Bandicoot\Context\PostProcessDecorator;
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
     * @var ListMergeStrategyInterface
     */
    protected $listMerge;
    /**
     * @var array
     */
    protected $helperResolver = array();

    /**
     * Factory constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface $propertyAccess
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface   $listMerge
     */
    public function __construct(PropertyAccessInterface $propertyAccess, ListMergeStrategyInterface $listMerge)
    {
        $this->propertyAccess = $propertyAccess;
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
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface
     */
    public function getListMerge()
    {
        return $this->listMerge;
    }

    /**
     * @param array $helperResolver
     */
    public function setHelperResolver(array $helperResolver)
    {
        $this->helperResolver = $helperResolver;
    }

    /**
     * @param Context $context
     * @param array $helperNames
     * @return Context
     * @throws \InvalidArgumentException
     */
    public function decorateWithHelpers(Context $context, array $helperNames)
    {
        $resolver = $this->helperResolver;
        $helperList = array_map(function($name) use($resolver) {
            if (array_key_exists($name, $resolver)) {
                return $resolver[$name];
            } else {
                trigger_error(sprintf('Helper "%s" not found.', $name));
            }
        }, $helperNames);
        $helperList = array_filter($helperList);
        if ($helperList) {
            $processor = function($value) use($helperList) {
                return array_reduce($helperList, function($carry, $helper) {
                    return call_user_func($helper, $carry);
                }, $value);
            };
            $context = new PostProcessDecorator($context, $processor);
        }

        return $context;
    }
}