<?php

namespace YevgenGrytsay\Bandicoot;
use YevgenGrytsay\Bandicoot\Context\ContextInterface;
use YevgenGrytsay\Bandicoot\Context\IteratorContext;
use YevgenGrytsay\Bandicoot\Context\ListContextInterface;
use YevgenGrytsay\Bandicoot\Context\RenderContext;
use YevgenGrytsay\Bandicoot\Context\UnwindArrayContext;
use YevgenGrytsay\Bandicoot\Context\UnwindArrayDelegatingContext;
use YevgenGrytsay\Bandicoot\Context\UnwindContext;
use YevgenGrytsay\Bandicoot\Context\ValueContext;
use YevgenGrytsay\Bandicoot\Context\ValueSelfContext;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class Builder
{
    /**
     * @param array $config
     *
     * @return RenderContext
     */
    public function render(array $config)
    {
        $context = new RenderContext($config);

        return $context;
    }

    /**
     * @param \Iterator $data
     *
     * @return IteratorContext
     */
    public function iterate(\Iterator $data)
    {
        $context = new IteratorContext($data);

        return $context;
    }

    /**
     * @param $name
     *
     * @return ValueContext
     */
    public function value($name)
    {
        $context = new ValueContext($name);

        return $context;
    }

    /**
     * @param array $config
     *
     * @return UnwindContext
     */
    public function unwind(array $config)
    {
        $context = new UnwindContext($config);

        return $context;
    }

    /**
     * @param $accessor
     *
     * @return UnwindArrayContext
     */
    public function unwindArray($accessor)
    {
        $context = new UnwindArrayContext($accessor);

        return $context;
    }

    /**
     * @param ContextInterface $context
     *
     * @return ContextInterface
     */
    public function unwindBy(ContextInterface $context)
    {
        $context = new UnwindArrayDelegatingContext($context);

        return $context;
    }

    /**
     * @return ValueSelfContext
     */
    public function self()
    {
        $context = new ValueSelfContext();

        return $context;
    }

    /**
     * @param ContextInterface $context
     *
     * @return ListContextInterface
     */
    public function _list(ContextInterface $context)
    {
        $context = new ListContextInterface($context);

        return $context;
    }
}