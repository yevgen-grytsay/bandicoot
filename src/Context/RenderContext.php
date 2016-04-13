<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Factory;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class RenderContext implements Context
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
     * 1) ["name"]:                             ["name" => ValueContext("name")]
     * 2) ["name" => "accessor"]:               ["name" => ValueContext("accessor")]
     * 3) ["name" => Context $ctx]:             ["name" => $ctx]
     * 4) ["name" => Closure $fnc]:             ["name" => ClosureContext($fnc)]
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
     * @param \SplStack $stack
     * 
     * @return array
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function run($value, \SplStack $stack)
    {
        $result = array();
        $listMerge = $this->getListMerge();
        /**
         * @var string|int $field
         * @var Context $context
         */
        foreach ($this->config as $field => $context) {
            list($field, $context) = $this->resolveContext($field, $context);
            $stack = clone $stack;
            $stack->push($value);
            $stack->push($field);
            $res = $context->run($value, $stack);
            $stack->pop();
            $stack->pop();
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
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    protected function resolveContext($field, $context)
    {
        $helperList = array();
        if (is_array($context)) {
            $ctx = array_shift($context);
            $helperList = array_shift($context);
            $context = $ctx;
        }
        if ($context instanceof Context) {
            $obj = $context;
        }
        else if (is_string($field) && is_string($context)) {
            $obj = new ValueContext($context, $this->factory->getPropertyAccessEngine());
        }
        else if (is_numeric($field) && is_string($context)) {
            $field = $context;
            $obj = new ValueContext($context, $this->factory->getPropertyAccessEngine());
        } else if (is_string($field) && is_callable($context)) {
            $obj = new CallableContext($context);
        } else {
            throw new \RuntimeException('Can not determine context');
        }

        if ($helperList) {
            $obj = $this->factory->decorateWithHelpers($obj, $helperList);
        }

        return array($field, $obj);
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