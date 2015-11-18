<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Factory;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class RenderContext implements ContextInterface
{
    /**
     * @var MergeStrategyInterface
     */
    protected $listMerge;
    /**
     * @var array
     */
    protected $config = array();
    /**
     * @var \YevgenGrytsay\Bandicoot\Factory
     */
    private $factory;

    /**
     * Each item in config can be defined in one of the following ways:
     * 1) ["name" => "accessor"]:       ["name" => ValueContext("accessor")]
     * 3) ["name" => ContextInterface]: ["name" => ValueContext("accessor")]
     * 2) ["accessor"]:                 ["accessor" => ValueContext("accessor")]
     *
     * @param array                            $config
     * @param \YevgenGrytsay\Bandicoot\Factory $factory
     */
    public function __construct(array $config, Factory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    /**
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface|null $listMerge
     *
     * @return $this
     */
    public function merge(MergeStrategyInterface $listMerge = null)
    {
        $this->listMerge = $listMerge;

        return $this;
    }

    /**
     * @param $value
     *
     * @return array
     * @throws \RuntimeException
     */
    public function run($value)
    {
        $result = array();
        $listMerge = $this->getListMerge();
        /**
         * @var string|int $field
         * @var ContextInterface $context
         */
        foreach ($this->config as $field => $context) {
            list($field, $context) = $this->resolveContext($field, $context);
            $res = $context->run($value);
            if ($context instanceof ListContext || $context instanceof IteratorContext) {
                $listMerge->merge($result, (array)$res, $field);
                /**
                 * Possible merge strategies:
                 * 1) $result[0] = $item_1; $result[1] = $item_2;
                 * 2) $result['field'] = [$item_1, $item_2]
                 *
                 */
            } else {
                $result[$field] = $res;
            }
        }

        return $result;
    }

    /**
     * @param $field
     * @param $context
     *
     * @return array
     * @throws \RuntimeException
     */
    protected function resolveContext($field, $context)
    {
        $result = null;
        if ($context instanceof ContextInterface) {
            $result = array($field, $context);
        }
        else if (is_string($field) && is_string($context)) {
            $result = array($field, new ValueContext($context, $this->factory->getPropertyAccessEngine()));
        }
        else if (is_numeric($field) && is_string($context)) {
            $result = array($context, new ValueContext($context, $this->factory->getPropertyAccessEngine()));
        }
        else {
            throw new \RuntimeException('Can not determine context');
        }

        return $result;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\ListMergeStrategyInterface
     */
    protected function getListMerge()
    {
        $merge = $this->listMerge;
        if (!$merge) {
            $merge = $this->factory->getListMerge();
        }

        return $merge;
    }
}