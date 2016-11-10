<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date: 13.04.16
 */

namespace YevgenGrytsay\Bandicoot\Context;


use YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\Factory;

class RendererFactory
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * RendererFactory constructor.
     * @param Factory $factory
     */
    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $config
     * @return RenderContext
     * @throws \Exception
     */
    public function createFromConfig(array $config)
    {
        $map = array();
        foreach ($config as $field => $context) {
            list($field, $context) = $this->resolveContext($field, $context);
            $map[$field] = $context;
        }
        
        return new RenderContext($map, $this->factory);
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
}