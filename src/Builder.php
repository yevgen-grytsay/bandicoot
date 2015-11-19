<?php

namespace YevgenGrytsay\Bandicoot;
use YevgenGrytsay\Bandicoot\Context\ContextInterface;
use YevgenGrytsay\Bandicoot\Context\ContextResolverInterface;
use YevgenGrytsay\Bandicoot\Context\IteratorContext;
use YevgenGrytsay\Bandicoot\Context\ListContext;
use YevgenGrytsay\Bandicoot\Context\RenderContext;
use YevgenGrytsay\Bandicoot\Context\UnwindArrayContext;
use YevgenGrytsay\Bandicoot\Context\UnwindContext;
use YevgenGrytsay\Bandicoot\Context\ValueContext;
use YevgenGrytsay\Bandicoot\Context\ValueSelfContext;
use YevgenGrytsay\Bandicoot\PropertyAccess\ConstantPropertyAccess;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class Builder
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * Builder constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array                                                     $config
     *
     * @param \YevgenGrytsay\Bandicoot\Context\ContextResolverInterface $resolver
     *
     * @return \YevgenGrytsay\Bandicoot\Context\RenderContext
     */
    public function render(array $config, ContextResolverInterface $resolver = null)
    {
        $context = new RenderContext($config, $this->factory, $resolver);

        return $context;
    }

    /**
     * @param \Iterator $data
     * @param array     $renderConfig
     *
     * @return \YevgenGrytsay\Bandicoot\Context\IteratorContext
     */
    public function each(\Iterator $data, array $renderConfig)
    {
        $render = $this->render($renderConfig);
        $context = new IteratorContext($data, $render, $this->factory->getMerge());

        return $context;
    }

    /**
     * @param $name
     *
     * @return ValueContext
     */
    public function value($name)
    {
        $context = new ValueContext($name, $this->getPropertyAccessEngine());

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
     * @param                                                        $accessor
     * @param array                                                  $renderConfig
     *
     * @return \YevgenGrytsay\Bandicoot\Context\UnwindArrayContext
     */
    public function unwindArray($accessor, array $renderConfig = null)
    {
        $render = null;
        if ($renderConfig) {
            $render = $this->render($renderConfig);
        }
        $accessor = new ConstantPropertyAccess($this->getPropertyAccessEngine(), $accessor);
        $context = new UnwindArrayContext($accessor, $this->factory->getMerge(), $render);

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
     * @return ListContext
     */
    public function _list(ContextInterface $context)
    {
        $context = new ListContext($context, $this->factory->getMerge());

        return $context;
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface
     */
    private function getPropertyAccessEngine()
    {
        return $this->factory->getPropertyAccessEngine();
    }
}