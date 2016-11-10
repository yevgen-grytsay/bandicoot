<?php
/**
 * @author: Yevgen Grytsay <yevgen_grytsay@mail.ru>
 * @date  : 19.11.15
 */

namespace YevgenGrytsay\Bandicoot\PropertyAccess;


use YevgenGrytsay\Bandicoot\PropertyAccess;

class ConstantPropertyAccess
{
    /**
     * @var PropertyAccess
     */
    private $engine;
    /**
     * @var
     */
    private $property;

    /**
     * ConstantPropertyAccess constructor.
     *
     * @param \YevgenGrytsay\Bandicoot\PropertyAccess $engine
     * @param                                                                 $property
     */
    public function __construct(PropertyAccess $engine, $property)
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