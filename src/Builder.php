<?php

namespace YevgenGrytsay\Bandicoot;
use YevgenGrytsay\Bandicoot\Context\CallableContext;
use YevgenGrytsay\Bandicoot\Context\ConstantContext;
use YevgenGrytsay\Bandicoot\Context\Context;
use YevgenGrytsay\Bandicoot\Context\FromMapContext;
use YevgenGrytsay\Bandicoot\Context\IteratorContext;
use YevgenGrytsay\Bandicoot\Context\ListContext;
use YevgenGrytsay\Bandicoot\Context\RenderContext;
use YevgenGrytsay\Bandicoot\Context\RendererFactory;
use YevgenGrytsay\Bandicoot\Context\UnwindArrayContext;
use YevgenGrytsay\Bandicoot\Context\UnwindContext;
use YevgenGrytsay\Bandicoot\Context\ValueContext;
use YevgenGrytsay\Bandicoot\Context\ValueSelfContext;
use YevgenGrytsay\Bandicoot\PropertyAccess\ConstantPropertyAccess;
use YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface;

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
     * @var RendererFactory
     */
    protected $renderFactory;

    /**
     * Builder constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
        $this->renderFactory = new RendererFactory($factory);
    }

    /**
     * @param array $config
     * @return RenderContext
     */
    public function render(array $config)
    {
        return $this->renderFactory->createFromConfig($config);
    }

    /**
     * @param array $config
     * @return \Closure
     */
    public function describe(array $config)
    {
        $context = $this->renderFactory->createFromConfig($config);

        return function($data = null) use($context) {
            $stack = new \SplStack();
            $stack->push('root');
            $stack->push($data);

            return $context->run($data, $stack);
        };
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

        return new IteratorContext($data, $render, $this->factory->getMerge());
    }

    /**
     * @param \Iterator $data
     * @param ContextFilterFactory $factory
     * @param array $renderConfig
     * @return IteratorContext
     */
    public function eachFilter(\Iterator $data, ContextFilterFactory $factory, array $renderConfig)
    {
        $render = $this->render($renderConfig);

        return new IteratorContext($data, $render, $this->factory->getMerge(), $factory);
    }

    /**
     * @param $name
     *
     * @return ValueContext
     */
    public function value($name)
    {
        return new ValueContext($name, $this->getPropertyAccessEngine());
    }

    /**
     * @param $value
     * @return ConstantContext
     */
    public function constant($value)
    {
        return new ConstantContext($value);
    }

    /**
     * @param $property
     * @param $value
     * @param $fallback
     * @return CallableContext
     * @throws \InvalidArgumentException
     */
    public function ifElse($property, $value, $fallback)
    {
        if (!$property instanceof PropertyAccessInterface) {
            $property = new ConstantPropertyAccess($this->factory->getPropertyAccessEngine(), $property);
        }
        
        return new CallableContext(function($data) use($property, $value, $fallback) {
            return $property->getValue($data) ? $value : $fallback;
        });
    }

    /**
     * @param $property
     * @param $against
     * @param $value
     * @param $fallback
     * @param bool $strict
     * @return CallableContext
     * @throws \InvalidArgumentException
     */
    public function ifEquals($property, $against, $value, $fallback, $strict = false)
    {
        if ($strict) {
            $eq = function($a, $b) { return $a === $b; };
        } else {
            $eq = function($a, $b) { return $a == $b; };
        }
        if (!$property instanceof PropertyAccessInterface) {
            $property = new ConstantPropertyAccess($this->factory->getPropertyAccessEngine(), $property);
        }

        return new CallableContext(function($data) use($property, $eq, $against, $value, $fallback) {
            $prop = $property->getValue($data);
            return $eq($prop, $value) ? $value : $fallback;
        });
    }
    
    /**
     * @param array $config
     *
     * @return UnwindContext
     */
    public function unwind(array $config)
    {
        return new UnwindContext($config);
    }

    /**
     * @param $property
     * @param array $config
     * @return ListContext
     */
    public function eachUnwind($property, array $config)
    {
        return new ListContext($this->unwindArray($property, $config), $this->factory->getMerge());
    }
    
    /**
     * @param string $property
     * @param array  $renderConfig
     *
     * @return \YevgenGrytsay\Bandicoot\Context\UnwindArrayContext
     */
    public function unwindArray($property, array $renderConfig = null)
    {
        $renderer = null;
        if ($renderConfig) {
            $renderer = $this->render($renderConfig);
        }
        $accessor = new ConstantPropertyAccess($this->getPropertyAccessEngine(), $property);

        return new UnwindArrayContext($accessor, $this->factory->getMerge(), $renderer);
    }

    /**
     * @param array|\ArrayAccess $source
     * @param string|ConstantPropertyAccess $keyAccessor
     * @param string|ConstantPropertyAccess|null $valueAccessor
     * @return FromMapContext
     * @throws \InvalidArgumentException
     */
    public function fromMap($source, $keyAccessor, $valueAccessor = null)
    {
        if (!$keyAccessor instanceof ConstantPropertyAccess) {
            $keyAccessor = new ConstantPropertyAccess($this->getPropertyAccessEngine(), $keyAccessor);
        }
        if (!$valueAccessor instanceof ConstantPropertyAccess && $valueAccessor !== null) {
            $valueAccessor = new ConstantPropertyAccess($this->getPropertyAccessEngine(), $valueAccessor);
        }
        
        return new FromMapContext($source, $keyAccessor, $valueAccessor);
    }

    /**
     * @return ValueSelfContext
     */
    public function self()
    {
        return new ValueSelfContext();
    }

    /**
     * @param Context $context
     *
     * @return ListContext
     */
    public function _list(Context $context)
    {
        return new ListContext($context, $this->factory->getMerge());
    }

    /**
     * @return \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface
     */
    private function getPropertyAccessEngine()
    {
        return $this->factory->getPropertyAccessEngine();
    }
}