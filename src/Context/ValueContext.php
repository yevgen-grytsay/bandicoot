<?php

namespace YevgenGrytsay\Bandicoot\Context;
use YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface;

/**
 * @author: Yevgen Grytsay <hrytsai@mti.ua>
 * @date  : 02.10.15
 */
class ValueContext implements ContextInterface
{
    /**
     * @var string
     */
    protected $accessor;
    /**
     * @var PropertyAccessInterface
     */
    private $propertyAccess;

    /**
     * ValueContext constructor.
     *
     * @param string                                                          $accessor
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface $propertyAccess
     */
    public function __construct($accessor, PropertyAccessInterface $propertyAccess)
    {
        $this->accessor = $accessor;
        $this->propertyAccess = $propertyAccess;
    }

    /**
     * @param $value
     *
     * @param \SplStack $stack
     * @return mixed
     */
    public function run($value, \SplStack $stack)
    {
        return $this->propertyAccess->getValue($value, $this->accessor);
    }
}