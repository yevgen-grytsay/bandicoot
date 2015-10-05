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
    protected $defaultMerge;
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
     * RenderContext constructor.
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
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $merge
     * @param \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface $listMerge
     *
     * @return $this
     */
    public function merge(MergeStrategyInterface $merge = null, MergeStrategyInterface $listMerge = null)
    {
        $this->defaultMerge = $merge;
        $this->listMerge = $listMerge;

        return $this;
    }

    /**
     * @param $value
     *
     * @return array
     */
    public function run($value)
    {
        $result = array();
        $merge = $this->getDefaultMerge();
        $listMerge = $this->getListMerge();
        /**
         * @var string|int $field
         * @var ContextInterface $context
         */
        foreach ($this->config as $field => $context) {
            $res = $context->run($value);
            if ($context instanceof ListContextInterface) {
                $listMerge->merge($result, $res, $field);
            } else {
                $merge->merge($result, $res, $field);
            }
        }

        return $result;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface
     */
    protected function getDefaultMerge()
    {
        $merge = $this->defaultMerge;
        if (!$merge) {
            $merge = $this->factory->getMerge();
        }

        return $merge;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface
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