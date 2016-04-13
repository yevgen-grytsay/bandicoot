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
    protected $fieldMap = array();
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
     * @param array                            $fieldMap
     * @param \YevgenGrytsay\Bandicoot\Factory $factory
     */
    public function __construct(array $fieldMap, Factory $factory)
    {
        $this->fieldMap = $fieldMap;
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
     */
    public function run($value, \SplStack $stack)
    {
        $result = array();
        $listMerge = $this->getListMerge();
        /**
         * @var string|int $field
         * @var Context $context
         */
        foreach ($this->fieldMap as $field => $context) {
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