<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 05.10.15
 */
namespace YevgenGrytsay\Bandicoot;

use YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Context\PostProcessDecorator;
use YevgenGrytsay\Bandicoot\MergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;
use YevgenGrytsay\Bandicoot\PropertyAccess;

class Factory
{
    /**
     * @var PropertyAccess
     */
    protected $propertyAccess;
    /**
     * @var MergeStrategy
     */
    protected $listMerge;
    /**
     * @var array
     */
    protected $helperResolver = array();

    /**
     * Factory constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess $propertyAccess
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy   $listMerge
     */
    public function __construct(PropertyAccess $propertyAccess, MergeStrategy $listMerge)
    {
        $this->propertyAccess = $propertyAccess;
        $this->listMerge = $listMerge;
    }

    /**
     * @returns PropertyAccess
     */
    public function getPropertyAccessEngine()
    {
        return $this->propertyAccess;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy
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