<?php
/**
 * @author: Yevgen Grytsay hrytsai@mti.ua
 * @date  : 19.11.15
 */

namespace YevgenGrytsay\Bandicoot\PropertyAccess;


class ConstantPropertyAccess
{
    /**
     * @var PropertyAccessInterface
     */
    private $engine;
    /**
     * @var
     */
    private $property;

    /**
     * ConstantPropertyAccess constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess\PropertyAccessInterface $engine
     * @param                                                                 $property
     */
    public function __construct(PropertyAccessInterface $engine, $property)
    {
        $this->engine = $engine;
        $this->property = $property;
    }

    /**
     * @param $arrayOrObject
     *
     * @return mixed
     */
    public function getValue($arrayOrObject)
    {
        return $this->engine->getValue($arrayOrObject, $this->property);
    }
}