<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\ArrayHelper;
use YevgenGrytsay\Bandicoot\MergeStrategy\ArrayPushMergeStrategy;
use YevgenGrytsay\Bandicoot\MergeStrategy\MergeStrategyInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class UnwindArrayContext implements ContextInterface
{
    /**
     * @var string
     */
    protected $mergeType = 'field';
    /**
     * @var string
     */
    protected $mergeKey;
    /**
     * @var string
     */
    protected $accessor;
    /**
     * @var ContextInterface
     */
    protected $context;

    /**
     * UnwindArrayContext constructor.
     *
     * @param string $accessor
     */
    public function __construct($accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function run($value)
    {
        /**
         * @var int|string $key
         * @var ContextInterface $context
         */
        $result = array();
        $merge = $this->createMerge();
        foreach ($this->_iterator($value) as $key => $value) {
            $ret = $this->context->run($value);
            $merge->merge($result, $ret, $key);
        }

        return $result;
    }

    /**
     * @param $value
     *
     * @return \ArrayIterator
     */
    protected function _iterator($value)
    {
        $data = ArrayHelper::getValue($value, $this->accessor);

        return new \ArrayIterator($data);
    }

    /**
     * @param ContextInterface $render
     *
     * @return $this
     */
    public function renderArray(ContextInterface $render)
    {
        $this->context = new ArrayRenderContext($render);

        return $this;
    }

    /**
     * @param array $config
     *
     * @return $this
     */
    public function render($config)
    {
        $this->context = new RenderContext($config);

        return $this;
    }

    /**
     * @param $type
     *
     * @return $this
     */
    public function merge($type)
    {
        $this->mergeType = $type;

        return $this;
    }

    /**
     * @return MergeStrategyInterface
     */
    protected function createMerge()
    {
        return new ArrayPushMergeStrategy();
    }
}